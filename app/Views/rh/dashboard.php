<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Tableau de bord RH<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Espace responsable<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/rh/demandes" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
    <i class="bi bi-inbox"></i> Voir les demandes
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<p>Test - Dashboard fonctionne!</p>
<p>Nombre en attente: <?= isset($nbEnAttente) ? $nbEnAttente : 'N/A' ?></p>
<p>Nombre de départements: <?= isset($partDept) ? count($partDept) : 'N/A' ?></p>

<?= $this->endSection() ?>