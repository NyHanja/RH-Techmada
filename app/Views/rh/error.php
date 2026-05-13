<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Erreur RH<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Espace responsable<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="data-card">
    <div class="data-card-head"><h3>Erreur</h3></div>
    <div style="padding:1.25rem">
        <p class="flash flash-error">Une erreur est survenue :</p>
        <pre style="white-space:pre-wrap;color:#a33;background:#fff;padding:10px;border-radius:6px;border:1px solid #eee"><?= esc($message) ?></pre>
        <p style="margin-top:8px"><a href="/" class="btn-secondary">Retour accueil</a> <a href="/rh/dashboard" class="btn-cancel">Réessayer</a></p>
    </div>
</div>

<?= $this->endSection() ?>