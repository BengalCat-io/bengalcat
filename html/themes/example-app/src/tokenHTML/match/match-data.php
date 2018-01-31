<?php if (!isset($data->matchData)) : ?>

<div class="well">
    No match data was retrieved.
</div>

<?php return; endif; ?>

<?php if (empty($data->matchData->data) && !empty($data->matchData->message)) : ?>

<div class="well">
    <?= $data->matchData->message; ?>
</div>

<?php return; endif; ?>

<div class="match-candidates">

    <?php
    $e = $data->matchData->external_listing;
    $c = $data->matchData->cr_listing;
    ?>

    <table class="table">
        <thead>
            <tr>
                <th>Listing</th>
                <th>Candidate</th>
            </tr>
        </thead>
        <tbody>
            <!-- NAME -->
            <tr>
                <td colspan="2">Facility Name</td>
            </tr>
            <tr>
                <td><?= $c->name; ?></td>
                <td>
                    <?= $e->name . (isset($e->name2) ? ' '. $e->name2 : ''); ?>
                </td>
            </tr>

            <!-- ADDRESS -->
            <tr>
                <td colspan="2">Address</td>
            </tr>
            <tr>
                <td>
                    <?= $c->address->street_address; ?><br>
                    <?= "<strong>{$c->address->city}, {$c->address->state}</strong> {$c->address->zip_code}" ;?>
                </td>
                <td>
                    <?= $e->address->street_address; ?><br>
                    <?= "<strong>{$e->address->city}, {$e->address->state}</strong> {$e->address->zip_code}" ;?>
                </td>
            </tr>

            <?= Bc\App\Utils\ListingMatcherUtil::getMatchSectionHtmlByKeys([
                'phones',
                'people',
                'urls',
            ], $c, $e); ?>
        </tbody>
    </table>

</div>
