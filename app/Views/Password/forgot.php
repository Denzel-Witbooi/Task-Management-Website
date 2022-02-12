<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?> 
   Forgot password
<?= $this->endSection() ?> 

<?= $this->section('content')?>
    <h1> Forgot password </h1>
 
    <?php if (session()->has('errors')): ?>
        <ul> 
            <?php foreach(session('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif;?>

    <?= form_open("/password/processforgot") ?>

    <div> 
        <label for="email">email</label>
        <input type="text" name="email" id="email" value="<?= old('email')?>">
    </div> 
        <button>  Send </button>
        <a href="<?= site_url("/")?>">Cancel</a>
     </form>

<?= $this->endSection() ?>