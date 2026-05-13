<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Départements<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="form-section">
    <form method="post" action="/admin/departements/new">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div>
                <label class="f-label">Nom</label>
                <input name="nom" class="f-input" required />
            </div>
            <div style="align-self:end">
                <button class="btn-forest" type="submit">Ajouter</button>
            </div>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head"><h3>Liste</h3></div>
    <table class="tbl">
        <thead><tr><th>Nom</th></tr></thead>
        <tbody>
            <?php if (empty($departements)): ?>
                <tr><td class="empty">Aucun département</td></tr>
            <?php else: ?>
                <?php foreach ($departements as $d): ?>
                    <tr><td><?= esc($d['nom']) ?></td></tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
