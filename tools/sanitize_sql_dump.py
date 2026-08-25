import argparse
import re
from pathlib import Path


def split_sql_values(row):
    values = []
    current = []
    in_string = False
    escape = False

    for ch in row:
        if escape:
            current.append(ch)
            escape = False
            continue
        if ch == "\\" and in_string:
            current.append(ch)
            escape = True
            continue
        if ch == "'":
            current.append(ch)
            in_string = not in_string
            continue
        if ch == "," and not in_string:
            values.append("".join(current).strip())
            current = []
            continue
        current.append(ch)

    values.append("".join(current).strip())
    return values


def sql_unquote(value):
    value = value.strip()
    if value.upper() == "NULL":
        return ""
    if len(value) >= 2 and value[0] == "'" and value[-1] == "'":
        return value[1:-1].replace("\\'", "'").replace("\\\\", "\\")
    return value


def sql_quote(value):
    return "'" + value.replace("\\", "\\\\").replace("'", "\\'") + "'"


def extract_tuples(values_sql):
    rows = []
    depth = 0
    start = None
    in_string = False
    escape = False

    for index, ch in enumerate(values_sql):
        if escape:
            escape = False
            continue
        if ch == "\\" and in_string:
            escape = True
            continue
        if ch == "'":
            in_string = not in_string
            continue
        if in_string:
            continue
        if ch == "(":
            if depth == 0:
                start = index + 1
            depth += 1
        elif ch == ")":
            depth -= 1
            if depth == 0 and start is not None:
                rows.append(values_sql[start:index])
                start = None
    return rows


def sanitize_user_insert(block):
    header_match = re.search(r"INSERT INTO `user` \((.*?)\) VALUES", block, re.S)
    if not header_match:
        return block

    columns = [part.strip().strip("`") for part in header_match.group(1).split(",")]
    column_index = {name: index for index, name in enumerate(columns)}
    values_sql = block[header_match.end():].rstrip().rstrip(";")

    sanitized_rows = []
    for row_sql in extract_tuples(values_sql):
        values = split_sql_values(row_sql)
        get = lambda name, default="0": values[column_index[name]] if name in column_index and column_index[name] < len(values) else default

        user_id = sql_unquote(get("user_id", "0")) or "0"
        applicant_type = sql_unquote(get("applicant_type", "'university'")) or "university"
        applicant_level = sql_unquote(get("applicant_level", "''"))
        user_type = sql_unquote(get("user_type", "'member'")) or "member"
        pdpa1 = sql_unquote(get("pdpa1", "0")) or "0"
        pdpa2 = sql_unquote(get("pdpa2", "0")) or "0"
        pdpa3 = sql_unquote(get("pdpa3", "0")) or "0"

        sanitized = [
            get("user_id", "0"),
            get("university_id", "0"),
            get("faculty_id", "0"),
            sql_quote(applicant_type),
            sql_quote(applicant_level),
            sql_quote(user_type),
            sql_quote(f"user{user_id}@example.test"),
            sql_quote("CHANGE_ME"),
            sql_quote(f"User {user_id}"),
            sql_quote("Example"),
            sql_quote("img/user.png"),
            sql_quote(""),
            sql_quote(""),
            sql_quote(""),
            sql_quote("0000-00-00"),
            sql_quote(""),
            pdpa1,
            pdpa2,
            pdpa3,
        ]
        sanitized_rows.append("(" + ", ".join(sanitized) + ")")

    prefix = block[:header_match.end()]
    return prefix + "\n" + ",\n".join(sanitized_rows) + ";\n"


def sanitize_dump(source, destination):
    text = source.read_text(encoding="utf-8-sig")
    lines = text.splitlines(keepends=True)
    output = []
    index = 0

    while index < len(lines):
        if lines[index].startswith("INSERT INTO `user`"):
            block = []
            while index < len(lines):
                block.append(lines[index])
                if lines[index].rstrip().endswith(";"):
                    index += 1
                    break
                index += 1
            output.append(sanitize_user_insert("".join(block)))
            continue
        output.append(lines[index])
        index += 1

    destination.parent.mkdir(parents=True, exist_ok=True)
    destination.write_text(
        "-- Sanitized for source control: real user names, emails, birthdays, phones, images, and passwords were replaced.\n"
        + "".join(output),
        encoding="utf-8",
    )


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("source", type=Path)
    parser.add_argument("destination", type=Path)
    args = parser.parse_args()
    sanitize_dump(args.source, args.destination)


if __name__ == "__main__":
    main()
