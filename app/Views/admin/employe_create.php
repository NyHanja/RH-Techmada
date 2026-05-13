<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Créer un employé<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="form-section">
    <form method="post" action="/admin/employes/new">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div>
                <label class="f-label">Prénom</label>
                <input name="prenom" class="f-input" required />
            </div>
            <div>
                <label class="f-label">Nom</label>
                <input name="nom" class="f-input" required />
            </div>
        </div>

        <div class="f-group">
            <label class="f-label">Email</label>
            <input name="email" type="email" class="f-input" required />
        </div>

        <div class="form-grid-2">
            <div>
                <label class="f-label">Département</label>
                <select name="departement_id" class="f-select">
                    <?php foreach ($departements as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= esc($d['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="f-label">Rôle</label>
                <select name="role" class="f-select" required>
                    <option value="employe">Employé</option>
                    <option value="rh">Responsable RH</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-forest" type="submit">Créer</button>
            <a href="/admin/employes" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
