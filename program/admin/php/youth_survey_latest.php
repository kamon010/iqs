<?php
function youth_latest_export_where($where,$status) {
    // Select the newest eligible response per account reference AND area.
    // Legacy/unlinked responses remain separate because their identity is unknown.
    $statusSql=$status==='all'?'':' AND newer.withdrawn=r.withdrawn';
    return $where.' AND (r.respondent_key IS NULL OR NOT EXISTS (
        SELECT 1 FROM youth_survey_responses newer
        JOIN youth_survey_response_details nd ON nd.response_id=newer.id
        WHERE newer.respondent_key=r.respondent_key
        AND newer.area_code <=> r.area_code'.$statusSql.'
        AND (newer.created_at>r.created_at OR (newer.created_at=r.created_at AND newer.id>r.id))
    ))';
}
