<div class="container-fluid">
    <h2 data-plugin="toggleclass" data-toggle-target=".has-sidebar" data-toggle-class="mobile-show-sidebar"></h2>

    <div class="sidebar-inner">

        <span></span>

        <h2>Datasets & History</h2>

        <h4>
            Datasets
        </h4>
        <ul>

            <?php if (!empty($data->datasets)) : ?>

            <li class="<?= $data->uri == '/any/matches/' ? 'active' : ''; ?>">
                <a href="/any/matches/">Any</a>
            </li>

            <?php foreach ($data->datasets as $dataset) : ?>

            <li class="<?= $data->uri == "/$dataset/matches/" ? 'active' : ''; ?>">
                <a href="/<?= $dataset; ?>/matches/"><?= ucwords($dataset); ?></a>
            </li>

            <?php endforeach; ?>

            <?php endif; ?>
        </ul>

        <?php if (!empty($data->decisionHistory)) : ?>
        <div class="match-history">
            <h4>Decision History</h4>
            <ul>
                <?php foreach ($data->decisionHistory as $decision) : ?>
                <li>
                    <a href="/match/<?= $decision->id; ?>/">
                        <div class="listing">
                            <h5>Listing</h5>
                            <p>
                                <?= $decision->listing_name; ?>
                                <br>
                                <?= $decision->listing_city; ?>, <?= $decision->listing_state; ?> <?= $decision->listing_zip_code; ?>
                            </p>
                        </div>
                        <div class="listing candidate">
                            <h5>Candidate</h5>
                            <p>
                                <?= $decision->candidate_name; ?>
                                <br>
                                <?= $decision->candidate_city; ?>, <?= $decision->candidate_state; ?> <?= $decision->candidate_zip_code; ?>
                            </p>
                            </p>
                        </div>

                        <?php if (!empty($decision->status)) : ?>

                        <div class="status">
                            Status <span class="badge"><?= $decision->status; ?></span>
                        </div>

                        <?php endif; ?>

                        <?php if (!empty($decision->api_status)) : ?>

                        <hr/>
                        <div class="status">
                            API Status <span class="badge"><?= $decision->api_status; ?></span>
                        </div>

                        <?php endif; ?>

                        <?php if (!empty($decision->status_failed)) : ?>

                        <div class="status">
                            Failed To Submit Decision to API <span class="badge"><?= $decision->status_failed; ?></span>
                        </div>
                        <hr/>

                        <?php endif; ?>


                        <?php if (!empty($decision->reviewed_by)) : ?>

                        <div class="status">
                            Reviewed By <span class="badge"><?= $decision->reviewed_by; ?></span>
                        </div>

                        <?php endif; ?>


                        <?php if (!empty($decision->match_quality)) : ?>

                        <div class="status">
                            Match Quality <span class="badge"><?= $decision->match_quality; ?></span>
                        </div>

                        <?php endif; ?>

                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <ul>

            <li class="">
                <a href="/decisions/">View All</a>
            </li>

        </ul>
        <?php endif; ?>
    </div>

</div>

