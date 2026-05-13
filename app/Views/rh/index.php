<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Demandes de congés<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Espace responsable<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/rh/dashboard" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
    <i class="bi bi-speedometer2"></i> Retour au dashboard
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="form-section">
    <form method="get" action="/rh/demandes" class="form-grid-2">
        <div>
            <label class="f-label">Filtrer par département</label>
            <select name="departement_id" class="f-select">
                <option value="">Tous</option>
                <?php foreach ($departements as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= (isset($dept) && $dept == $d['id']) ? 'selected' : '' ?>><?= esc($d['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="f-label">Statut</label>
            <select name="statut" class="f-select">
                <?php $opts = ['en_attente' => 'En attente', 'approuve' => 'Approuvée', 'refuse' => 'Refusée', 'tous' => 'Tous']; ?>
                <?php foreach ($opts as $k => $v): ?>
                    <option value="<?= $k ?>" <?= (isset($statut) && $statut === $k) ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions" style="grid-column:1/-1">
            <button type="submit" class="btn-secondary">Appliquer</button>
            <a href="/rh/demandes" class="btn-cancel">Réinitialiser</a>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head"><h3>Liste des demandes</h3></div>
    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employé</th>
                <th>Type</th>
                <th>Département</th>
                <th>Période</th>
                <th>Jours</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($demandes)): ?>
                <tr><td colspan="8" class="empty">Aucune demande trouvée</td></tr>
            <?php else: ?>
                <?php foreach ($demandes as $row): ?>
                <tr>
                    <td class="td-mono"><?= $row['id'] ?></td>
                    <td class="td-name"><?= esc($row['prenom'] . ' ' . $row['nom']) ?></td>
                    <td><span class="type-badge t-<?= strtolower(str_replace(' ', '-', $row['type_conge'])) ?>"><?= esc($row['type_conge']) ?></span></td>
                    <td class="td-muted"><?= esc($row['departement']) ?></td>
                    <td><?= esc($row['date_debut']) ?> → <?= esc($row['date_fin']) ?></td>
                    <td class="td-mono"><?= $row['nb_jours'] ?></td>
                    <td>
                        <?php if ($row['statut'] === 'en_attente'): ?>
                            <span class="statut s-attente">En attente</span>
                        <?php elseif ($row['statut'] === 'approuve'): ?>
                            <span class="statut s-approuvee">Approuvée</span>
                        <?php else: ?>
                            <span class="statut s-refusee">Refusée</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['statut'] === 'en_attente'): ?>
                            <div class="action-btns">
                                <form method="post" action="/rh/demandes/approuver/<?= $row['id'] ?>" onsubmit="return confirm('Approuver cette demande ?')">
                                    <?= csrf_field() ?>
                                    <button class="btn-sm btn-approve" type="submit">Approuver</button>
                                </form>

                                <button class="btn-sm btn-refuse" type="button" onclick="toggleRefuse(<?= $row['id'] ?>)">Refuser</button>
                            </div>

                            <div id="refuse-<?= $row['id'] ?>" style="display:none;margin-top:8px">
                                <form method="post" action="/rh/demandes/refuser/<?= $row['id'] ?>">
                                    <?= csrf_field() ?>
                                    <div class="f-group">
                                        <textarea name="commentaire_rh" class="f-textarea" placeholder="Motif du refus (optionnel)"></textarea>
                                    </div>
                                    <div class="form-actions">
                                        <button class="btn-sm btn-refuse" type="submit">Envoyer le refus</button>
                                        <button type="button" class="btn-sm btn-cancel" onclick="toggleRefuse(<?= $row['id'] ?>)">Annuler</button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <a class="btn-sm btn-view" href="#">Voir</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function toggleRefuse(id){
    const el = document.getElementById('refuse-'+id);
    if(!el) return;
    el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}
</script>

<?= $this->endSection() ?>
