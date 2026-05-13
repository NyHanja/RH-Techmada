<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>
Connexion
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form method="POST" action="/login" class="auth-form" style="max-width: 400px; margin: 2rem auto; background: white; padding: 2rem; border-radius: 12px; border: 1px solid #dde8e1;">
    <?= csrf_field() ?>
    
    <h2 style="text-align: center; margin-bottom: 1.5rem; font-family: 'Playfair Display', serif; color: #1c2b1e;">Connexion</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="padding: 11px 14px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem; border: 1px solid #f0b8b2; display: flex; align-items: center; gap: 9px; background: #fdf0ee; color: #c0392b;">
            <i class="bi bi-exclamation-circle-fill"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div style="margin-bottom: 1rem;">
        <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Email *</label>
        <input type="email" name="email" value="<?= old('email') ?>" placeholder="vous@techmada.mg" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; font-family: 'DM Sans', sans-serif;" required/>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Mot de passe *</label>
        <input type="password" name="password" placeholder="••••••••" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; font-family: 'DM Sans', sans-serif;" required/>
    </div>

    <button type="submit" style="width: 100%; background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 11px 20px; font-weight: 500; font-size: 0.9rem; cursor: pointer;">
        Se connecter
    </button>
</form>

<?= $this->endSection() ?>
