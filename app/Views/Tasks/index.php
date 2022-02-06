<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?> 
    Tasks
<?= $this->endSection() ?>

<?= $this->section("content") ?>

    <h1>Tasks</h1>

    <a href="<?= site_url("/tasks/new")?>">New Task</a>
    <!-- We can just check the $tasks variable like this 
        As a none empty array is the boolean equivalent of 
        "true". Empty array the same as "false"
    -->
    <?php if($tasks): ?> 
        <ul> 
        <?php foreach($tasks as $task): ?>
            <li> 
                <a href="<?= site_url("/tasks/show/" . $task->id) ?>">
                    <?=  esc($task->description) ?>
                </a>
            </li> 
        <?php endforeach; ?>
    </ul>

    <?= $pager->links()?>

    <?php else: ?>
        <p>No Tasks Found.</p>
    <?php endif;?>

<?= $this->endSection() ?>