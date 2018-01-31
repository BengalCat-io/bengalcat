<div class="container-fluid">
    <h2 data-plugin="toggleclass" data-toggle-target=".has-sidebar" data-toggle-class="mobile-show-sidebar"></h2>

    <div class="sidebar-inner">

        <span></span>

        <h2>Filters</h2>

        <ul>
            <li class="<?= empty($data->appliedFilters) ? 'active' : ''; ?>">
                <a href="/decisions/">Show All Decisions</a>
            </li>
        </ul>

        <form class="decision-filters-form" action="/decisions/" method="get" data-plugin="clickyform" data-clicky-active-class="active">
            <h4>
                <span class="fa fa-filter"></span>
                Whose
            </h4>
            <ul>
                <li class="<?= isset($data->appliedFilters['user_id']) && $data->appliedFilters['user_id'] == $data->currentUser->user_id ? 'active' : ''; ?>" data-clicky="whose" data-value="mine">
                    <a href="javascript:void(0);">Only Mine</a>
                </li>
                <li class="<?= isset($data->appliedFilters['user_id']) && $data->appliedFilters['user_id'] != $data->currentUser->user_id  ? 'active' : ''; ?>" data-clicky="whose">
                    <input type="text" name="whose" placeholder="Type a user id" value="<?= isset($data->appliedFilters['user_id']) && $data->appliedFilters['user_id'] != $data->currentUser->user_id ? $data->appliedFilters['user_id'] : ''; ?>"/>
                </li>
            </ul>

            <h4>
                <span class="fa fa-filter"></span>
                Datasets
            </h4>
            <ul>
                <?php foreach ($data->datasets as $type) : ?>
                <li class="<?= isset($data->appliedFilters['type']) && $data->appliedFilters['type'] == $type ? 'active' : ''; ?>" data-clicky="type" data-value="<?= $type; ?>">
                    <a href="javascript:void(0);"><?= ucwords($type); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>

            <h4>
                <span class="fa fa-filter"></span>
                Match Status
            </h4>
            <ul>
                <?php foreach ($data->statusTypes as $status) : ?>
                <li class="<?= isset($data->appliedFilters['status']) && $data->appliedFilters['status'] == $status ? 'active' : ''; ?>" data-clicky="status" data-value="<?= $status; ?>">
                    <a href="javascript:void(0);"><?= ucwords($status); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>

            <ul>
                <li class="">
                    <h4><input type="submit" value="Apply Filters"/></h4>
                </li>
            </ul>
        </form>
    </div>

</div>

