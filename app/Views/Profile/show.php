<?= $this->extend('layouts/default') ?> 

<?= $this->section('title') ?>  
    Profile
<?= $this->endSection() ?>  

<?= $this->section('content') ?>

    <h1>Profile</h1> 

    <!-- Use description list element to display the  
    objects attributes -->
    <!-- And escape the name and email as it is untrusted content -->
    <dl> 
        <dt>Name</dt>
        <dd><?= esc($user->name)?></dd>

        <dt>email</dt>
        <dd><?= esc($user->email)?></dd>
    </dl>

    <a href="<?= site_url("/profile/edit")?>"> Edit </a>
<?= $this->endSection() ?>
