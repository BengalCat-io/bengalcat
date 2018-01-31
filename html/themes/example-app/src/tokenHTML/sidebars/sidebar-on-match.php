<div class="container-fluid">
    <h2 data-plugin="toggleclass" data-toggle-target=".has-sidebar" data-toggle-class="mobile-show-sidebar"></h2>

    <div class="sidebar-inner">

        <span></span>

        <h2>Match Info</h2>

        <h4>Dataset</h4>
        <ul>
            <?php if (!empty($data->matchInfo->type)) : ?>

            <li class="active">
                <a href="/<?= $data->matchInfo->type; ?>/matches/"><?= ucwords($data->matchInfo->type); ?></a>
            </li>

            <?php endif; ?>
        </ul>

        <div class="match-details">
            <h4>Details</h4>
            <ul>
                <li>
                    <div class="detail-wrapper">
                        <?php if (!empty($data->matchInfo->status)) : ?>

                        <div class="status">
                            Status <span class="badge"><?= $data->matchInfo->status; ?></span>
                        </div>

                        <?php endif; ?>

                        <?php if (!empty($data->matchInfo->api_status)) : ?>

                        <hr/>
                        <div class="status">
                            API Status <span class="badge"><?= $data->matchInfo->api_status; ?></span>
                        </div>

                        <?php endif; ?>

                        <?php if (!empty($data->matchInfo->status_failed)) : ?>

                        <div class="status">
                            Failed To Submit Decision to API <span class="badge"><?= $data->matchInfo->status_failed; ?></span>
                        </div>
                        <hr/>

                        <?php endif; ?>


                        <?php if (!empty($data->matchInfo->reviewed_by)) : ?>

                        <div class="status">
                            Reviewed By <span class="badge"><?= $data->matchInfo->reviewed_by; ?></span>
                        </div>

                        <?php endif; ?>


                        <?php if (!empty($data->matchInfo->match_quality)) : ?>

                        <div class="status">
                            Match Quality <span class="badge"><?= $data->matchInfo->match_quality; ?></span>
                        </div>

                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

