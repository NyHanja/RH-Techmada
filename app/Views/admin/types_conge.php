<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Types de congé<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="form-section">
    <form method="post" action="/admin/types-conge/new">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div>
                <label class="f-label">Libellé</label>
                <input name="nom" class="f-input" required />
            </div>
            <div>
                <label class="f-label">Jours par défaut</label>
                <input name="jours_par_defaut" type="number" class="f-input" value="0" required />
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit">Ajouter</button>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head"><h3>Types existants</h3></div>
    <table class="tbl">
        <thead><tr><th>Libellé</th><th>Jours</th></tr></thead>
        <tbody>
            <?php if (empty($typesConge)): ?>
                <tr><td colspan="2" class="empty">Aucun type</td></tr>
            <?php else: ?>
                <?php foreach ($typesConge as $t): ?>
                    <tr><td><?= esc($t['libelle'] ?? $t['nom']) ?></td><td class="td-mono"><?= esc($t['jours_annuels'] ?? $t['jours_par_defaut'] ?? '') ?></td></tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
