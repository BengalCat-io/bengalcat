<form class="feedback-form" action="/submit/feedback/" method="post">
    <label>
        The email we may reply to
        <input type="text" placeholder="Reply Email" name="email" readonly="readonly" value="<?= $data->currentUser->email; ?>"/>
    </label>
    <?php if (empty((array) $data->matchDbData)) : ?>
    <label>
        If you have the url of a match to report, paste it here, or use the form on that page.
        <input type="text" placeholder="URL (optional)" name="url"/>
    </label>
    <?php else : ?>
    <input type="hidden" name="match_url" value="<?= $data->matchDbData->url; ?>" />
    <input type="hidden" name="listing_id" value="<?= $data->matchDbData->listing_id; ?>" />
    <input type="hidden" name="candidate_id" value="<?= $data->matchDbData->candidate_id; ?>" />
    <input type="hidden" name="type" value="<?= $data->matchDbData->type; ?>" />
    <?php endif; ?>
    <textarea name="message" placeholder="Report or give feedback here..."></textarea>
    <input type="hidden" name="user_id" value="<?= $data->currentUser->user_id; ?>"></textarea>
    <button class="feedback-form-submit" name="is_match_feedback" type="submit" value="<?= (empty((array) $data->matchDbData)) ? 'false': 'true'; ?>">Send it!</button>
</form>