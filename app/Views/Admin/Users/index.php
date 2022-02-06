<?= $this->extend("layouts/default") ?>

<?= $this->section("title") ?> 
    Users
<?= $this->endSection() ?>

<?= $this->section("content") ?>

    <h1>Users</h1>

    <a href="<?= site_url("/admin/users/new")?>">New User</a>
    <!-- We can just check the $tasks variable like this 
        As a none empty array is the boolean equivalent of 
        "true". Empty array the same as "false"
    -->
    <?php if($users): ?> 
        <table> 
            <thead>
                <tr> 
                    <th>Name</th>
                    <th>email</th>
                    <th>Active</th>
                    <th>Administrator</th>
                    <th>Created at</th>
                </tr> 

            </thead>
           <tbody> 
           <?php foreach($users as $user): ?>
                <tr> 
                    <td>
                        <a href="<?= site_url("/admin/users/show/" . $user->id) ?>">
                            <?=  esc($user->name) ?>
                        </a>
                    </td>
                    <td><?= esc($user->email)?></td>
                    <td><?= $user->is_active? 'yes' : 'no' ?></td>
                    <!-- We use the ternary operator to indicate yes if true and no if false -->
                    <td><?= $user->is_admin? 'yes' : 'no' ?></td>
                    <td><?= $user->created_at ?></td>

                </tr> 
            <?php endforeach; ?>
           </tbody>
        </table>

    <?= $pager->simpleLinks()?>

    <?php else: ?>
        <p>No Users Found.</p>
    <?php endif;?>

<?= $this->endSection() ?>