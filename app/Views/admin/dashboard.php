<?= $this->extend('layout/app') ?>

<?= $this->section('page_title') ?>Vue d'ensemble<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>Administration<?= $this->endSection() ?>

<?= $this->section('topbar_actions') ?>
<a href="/admin/employes/new" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
    <i class="bi bi-person-plus"></i> Ajouter un employé
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="metrics">

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-forest"><i class="bi bi-people"></i></div>
        </div>
        <div class="metric-val"><?= $nbEmployesActifs ?></div>
        <div class="metric-label">Employés actifs</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div>
        </div>
        <div class="metric-val"><?= $nbEnAttente ?></div>
        <div class="metric-label">Demandes en attente</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-green"><i class="bi bi-calendar-check"></i></div>
        </div>
        <div class="metric-val"><?= $nbApprouvesMois ?></div>
        <div class="metric-label">Approuvées ce mois</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-blue"><i class="bi bi-building"></i></div>
        </div>
        <div class="metric-val"><?= $nbDepartements ?></div>
        <div class="metric-label">Départements</div>
    </div>

    <div class="metric">
        <div class="metric-top">
            <div class="metric-icon mi-red"><i class="bi bi-person-slash"></i></div>
        </div>
        <div class="metric-val"><?= $nbAbsentsAujourdhui ?></div>
        <div class="metric-label">Absents aujourd'hui</div>
    </div>

</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start">

    <div class="data-card" style="margin:0">
        <div class="data-card-head">
            <h3>Demandes récentes</h3>
            <a href="/rh/demandes" style="font-size:.8rem;color:var(--forest);text-decoration:none">Tout voir →</a>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Type</th>
                    <th>Durée</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($demandesRecentes)): ?>
                    <tr>
                        <td colspan="4" class="empty">
                            <i class="bi bi-inbox"></i>
                            <p>Aucune demande récente.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($demandesRecentes as $d):
                        $initiales = strtoupper(substr($d['prenom'], 0, 1) . substr($d['nom'], 0, 1));

                        $statutClass = match($d['statut']) {
                            'en_attente' => 's-attente',
                            'approuvee'  => 's-approuvee',
                            'refusee'    => 's-refusee',
                            'annulee'    => 's-annulee',
                            default      => ''
                        };

                        $typeClass = match(strtolower($d['type_conge'])) {
                            'congé annuel', 'annuel' => 't-annuel',
                            'congé maladie', 'maladie' => 't-maladie',
                            'congé spécial', 'spécial' => 't-special',
                            default => 't-sans-solde'
                        };
                    ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:7px">
                                <div class="avatar av-green" style="width:28px;height:28px;font-size:.62rem">
                                    <?= esc($initiales) ?>
                                </div>
                                <span class="td-name" style="font-size:.84rem">
                                    <?= esc($d['prenom'] . ' ' . $d['nom']) ?>
                                </span>
                            </div>
                        </td>
                        <td><span class="type-badge <?= $typeClass ?>"><?= esc($d['type_conge']) ?></span></td>
                        <td class="td-mono"><?= $d['nb_jours'] ?> j</td>
                        <td><span class="statut <?= $statutClass ?>"><?= $d['statut'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="display:flex;flex-direction:column;gap:1rem">

        <div class="data-card" style="margin:0">
            <div class="data-card-head">
                <h3><i class="bi bi-person-slash" style="color:var(--muted);margin-right:5px"></i>Absents aujourd'hui</h3>
            </div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
                <?php if (empty($absentsAujourdhui)): ?>
                    <p style="font-size:.82rem;color:var(--muted);margin:0">Aucun absent aujourd'hui.</p>
                <?php else: ?>
                    <?php foreach ($absentsAujourdhui as $a):
                        $initiales = strtoupper(substr($a['prenom'], 0, 1) . substr($a['nom'], 0, 1));
                    ?>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="avatar av-green" style="width:30px;height:30px;font-size:.65rem">
                            <?= esc($initiales) ?>
                        </div>
                        <div>
                            <div style="font-size:.83rem;font-weight:500;color:var(--ink)">
                                <?= esc($a['prenom'] . ' ' . $a['nom']) ?>
                            </div>
                            <div style="font-size:.72rem;color:var(--muted)">
                                <?= esc($a['type_conge']) ?> · retour <?= date('d/m', strtotime($a['date_fin'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($nbSoldesCritiques > 0): ?>
        <div class="flash flash-warn" style="margin:0">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span style="font-size:.8rem">
                <?= $nbSoldesCritiques ?> employé<?= $nbSoldesCritiques > 1 ? 's ont' : ' a' ?> un solde critique (≤ 2 jours).
                <a href="/admin/soldes" style="color:var(--warn);font-weight:500">Voir les soldes →</a>
            </span>
        </div>
        <?php endif; ?>

        <div class="data-card" style="margin:0">
            <div class="data-card-head">
                <h3><i class="bi bi-bar-chart" style="color:var(--muted);margin-right:5px"></i>Congés ce mois par type</h3>
            </div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.6rem">
                <?php if (empty($congesParType)): ?>
                    <p style="font-size:.82rem;color:var(--muted);margin:0">Aucune donnée ce mois.</p>
                <?php else: ?>
                    <?php foreach ($congesParType as $c): ?>
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:.82rem;color:var(--ink)"><?= esc($c['type_conge']) ?></span>
                        <span style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--forest);font-weight:500">
                            <?= $c['nb'] ?> demande<?= $c['nb'] > 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
