<h2 class="text-center">
    <a href="#submit-decision"><span class="fa fa-chevron-down text-highlight"></span></a>
</h2>

<form action="/submit/decision/" method="post">
    <div class="options options-submit-decision">
        <div class="option">

            <h2 id="submit-decision" data-hash="#submit-decision" data-plugin="goscroll" class="text-center">Submit Decision</h2>

            <p class="text-center">
                Carefully Review the Match Candidates and submit your decision.
            </p>

            <input type="hidden" name="match_uri"           value="[:match uri]" />
            <input type="hidden" name="redirect_uri"        value="<?= $data->uri; ?>" />
            <input type="hidden" name="external[id]"        value="<?= $data->matchData->external_listing->listing_id; ?>"/>
            <input type="hidden" name="external[name]"      value="<?= $data->matchData->external_listing->name . (isset($data->matchData->external_listing->name2) ? ' '. $data->matchData->external_listing->name2 : ''); ?>"/>
            <input type="hidden" name="external[city]"      value="<?= $data->matchData->external_listing->address->city; ?>"/>
            <input type="hidden" name="external[state]"     value="<?= $data->matchData->external_listing->address->state; ?>"/>
            <input type="hidden" name="external[zip_code]"  value="<?= $data->matchData->external_listing->address->zip_code; ?>"/>
            <input type="hidden" name="cr[id]"              value="<?= $data->matchData->cr_listing->listing_id; ?>"/>
            <input type="hidden" name="cr[name]"            value="<?= $data->matchData->cr_listing->name; ?>"/>
            <input type="hidden" name="cr[city]"            value="<?= $data->matchData->cr_listing->address->city; ?>"/>
            <input type="hidden" name="cr[state]"           value="<?= $data->matchData->cr_listing->address->state; ?>"/>
            <input type="hidden" name="cr[zip_code]"        value="<?= $data->matchData->cr_listing->address->zip_code; ?>"/>
            <input type="hidden" name="type"                value="<?= $data->matchData->type; ?>"/>
            <input type="hidden" name="match_quality"       value="<?= $data->matchData->match_quality; ?>"/>

            <p class="text-center p-wide">
                <button type="submit" name="decision" value="matched">
                    <img src="<?= Bc\App\Core\Util::getAsset(IMAGE_DIR, 'match.png'); ?>"/>
                    They Match
                </button>
                <button type="submit" name="decision" value="no match">
                    <img src="<?= Bc\App\Core\Util::getAsset(IMAGE_DIR, 'different.png'); ?>"/>
                    They're Different
                </button>
                <button type="submit" name="decision" value="skip">
                    <img src="<?= Bc\App\Core\Util::getAsset(IMAGE_DIR, 'skip.png'); ?>"/>
                    Skip
                </button>
            </p>

            <hr/>
            
            <p>
                Remember, we are looking for <strong>exact</strong> <i>Listing</i> matches,
                as opposed to <i>Account</i> matches (where the facility locations are different, but owned and/or operated by same organization).
            </p>
            <p>ie. A similar corporate website is not enough to be deemed an exact match.</p>

            <p>You can find out more what we are looking for <a href="/help/#what-to-look-for">here</a>, at our help page.</p>
        </div>
    </div>
</form>