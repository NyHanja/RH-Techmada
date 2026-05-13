<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>
Nouvelle demande de congé
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
Employé > Demandes > Nouvelle
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->has('error')): ?>
    <div style="background: #fee; border: 1px solid #fc9999; border-radius: 8px; color: #c33; padding: 12px 15px; margin-bottom: 1.5rem;">
        <strong>Erreur:</strong> <?= session('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->has('success')): ?>
    <div style="background: #efe; border: 1px solid #9c9; border-radius: 8px; color: #3c3; padding: 12px 15px; margin-bottom: 1.5rem;">
        <strong>Succès:</strong> <?= session('success') ?>
    </div>
<?php endif; ?>

<form method="POST" action="/employe/demandes/new" style="max-width: 600px;">
    <?= csrf_field() ?>

    <div style="background: white; border: 1px solid #dde8e1; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Type de congé *</label>
            <select name="type_conge_id" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; background: white; color: #1c2b1e;" required>
                <option value="">Sélectionner un type...</option>
                <?php foreach ($types_conge ?? [] as $type): ?>
                    <option value="<?= $type['id'] ?>"><?= $type['libelle'] ?? 'N/A' ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Date début *</label>
                <input type="date" name="date_debut" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Date fin *</label>
                <input type="date" name="date_fin" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem;" required>
            </div>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label style="font-size: 0.8rem; font-weight: 500; color: #1c2b1e; display: block; margin-bottom: 5px;">Motif</label>
            <textarea name="motif" style="width: 100%; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 10px 12px; font-size: 0.875rem; min-height: 80px; font-family: 'DM Sans', sans-serif;" placeholder="Raison de la demande (optionnel)"></textarea>
        </div>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="submit" style="background: #2d5a3d; color: white; border: none; border-radius: 8px; padding: 11px 20px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <i class="bi bi-send"></i> Soumettre
        </button>
        <a href="/employe/demandes" style="background: white; color: #7a8f80; border: 1.5px solid #dde8e1; border-radius: 8px; padding: 9px 16px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            Retour
        </a>
    </div>
</form>

<?= $this->endSection() ?>
