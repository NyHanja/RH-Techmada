<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --ink:      #1c2b1e;
  --forest:   #2d5a3d;
  --forest2:  #3d7a52;
  --leaf:     #5fa876;
  --mint:     #d4ede0;
  --cream:    #f8f6f1;
  --white:    #ffffff;
  --border:   #dde8e1;
  --muted:    #7a8f80;
  --danger:   #c0392b;
  --danger-bg:#fdf0ee;
  --danger-br:#f0b8b2;
  --warn:     #b8750a;
  --warn-bg:  #fef9ee;
  --warn-br:  #f5d98a;
  --success:  #1e6b3f;
  --success-bg:#edf7f2;
  --success-br:#8fd4aa;
  --info:     #1a4f7a;
  --info-bg:  #eaf2fb;
  --info-br:  #8fbde8;
  --sidebar-w:240px;
  --topbar-h: 62px;
}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
code,pre,.mono{font-family:'DM Mono',monospace}

/* ─── LAYOUT ────────────────────────────── */
.app-wrap{display:flex;min-height:100vh}
.sidebar{width:var(--sidebar-w);background:var(--ink);display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-brand{padding:1.4rem 1.2rem 1rem;display:flex;align-items:center;gap:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.sidebar-logo-icon{width:34px;height:34px;background:var(--forest);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sidebar-logo-icon i{color:var(--white);font-size:1.1rem}
.sidebar-brand-name{font-family:'Playfair Display',serif;font-size:1rem;color:var(--white);line-height:1.2}
.sidebar-brand-name span{display:block;font-size:.65rem;font-family:'DM Sans',sans-serif;font-weight:400;color:rgba(255,255,255,.35);letter-spacing:.05em;text-transform:uppercase}
.sidebar-section{padding:.75rem 1.1rem .3rem;font-size:.62rem;font-weight:500;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.25);margin-top:.25rem}
.sidebar-nav{list-style:none;padding:0 .75rem;margin:0}
.sidebar-nav li{margin-bottom:2px}
.sidebar-nav li a{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.85rem;font-weight:400;transition:all .15s}
.sidebar-nav li a:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.9)}
.sidebar-nav li a.active{background:var(--forest);color:var(--white)}
.sidebar-nav li a i{font-size:1.05rem;flex-shrink:0}
.nav-badge{margin-left:auto;font-size:.65rem;padding:2px 7px;border-radius:10px;background:rgba(255,255,255,.12);color:var(--white)}
.nav-badge.alert{background:var(--danger);color:var(--white)}
.sidebar-user{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.06);margin-top:auto}
.s-user-row{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px}
.avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:500;color:var(--white);flex-shrink:0;font-family:'DM Mono',monospace}
.av-green{background:var(--forest2)}
.av-blue{background:#1a4f7a}
.av-amber{background:#b8750a}
.user-name{font-size:.825rem;font-weight:500;color:var(--white);line-height:1.2}
.user-role{font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.06em}

/* ─── MAIN ──────────────────────────────── */
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:var(--ink)}
.topbar-breadcrumb{font-size:.78rem;color:var(--muted)}
.topbar-actions{margin-left:auto;display:flex;align-items:center;gap:8px}
.content{padding:1.75rem;flex:1}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
.btn-forest:hover{background:var(--forest2);color:var(--white)}

/* ─── FLASH ─────────────────────────────── */
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.flash-warn{background:var(--warn-bg);color:var(--warn);border-color:var(--warn-br)}
.flash-info{background:var(--info-bg);color:var(--info);border-color:var(--info-br)}

/* ─── METRICS ───────────────────────────── */
.metrics{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:1rem;margin-bottom:1.75rem}
.metric{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem}
.metric-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
.metric-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem}
.mi-green{background:var(--success-bg);color:var(--success)}
.mi-amber{background:var(--warn-bg);color:var(--warn)}
.mi-red{background:var(--danger-bg);color:var(--danger)}
.mi-blue{background:var(--info-bg);color:var(--info)}
.mi-forest{background:var(--mint);color:var(--forest)}
.metric-val{font-family:'DM Mono',monospace;font-size:1.75rem;font-weight:500;color:var(--ink);line-height:1}
.metric-label{font-size:.775rem;color:var(--muted);margin-top:4px}

/* ─── DATA CARD + TABLE ─────────────────── */
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.data-card-head h3{font-family:'Playfair Display',serif;font-size:.95rem;margin:0;font-weight:600;color:var(--ink)}
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead th{padding:9px 14px;font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream);border-bottom:1px solid var(--border);text-align:left}
.tbl tbody tr{border-bottom:1px solid var(--border);transition:background .1s}
.tbl tbody tr:last-child{border-bottom:none}
.tbl tbody tr:hover{background:var(--cream)}
.tbl td{padding:12px 14px;color:var(--ink);vertical-align:middle}
.td-name{font-weight:500}
.td-muted{color:var(--muted)}
.td-mono{font-family:'DM Mono',monospace;font-size:.8rem}

/* ─── BADGES ────────────────────────────── */
.statut{display:inline-flex;align-items:center;gap:5px;font-size:.7rem;font-weight:500;padding:4px 9px;border-radius:12px}
.statut::before{content:'';width:5px;height:5px;border-radius:50%;display:inline-block;flex-shrink:0}
.s-attente{background:var(--warn-bg);color:var(--warn)}.s-attente::before{background:var(--warn)}
.s-approuvee{background:var(--success-bg);color:var(--success)}.s-approuvee::before{background:var(--success)}
.s-refusee{background:var(--danger-bg);color:var(--danger)}.s-refusee::before{background:var(--danger)}
.s-annulee{background:#f1efe8;color:#7a8f80}.s-annulee::before{background:#b4b2a9}
.type-badge{display:inline-block;font-size:.68rem;font-weight:500;padding:3px 8px;border-radius:4px}
.t-annuel{background:var(--mint);color:var(--forest)}
.t-maladie{background:var(--info-bg);color:var(--info)}
.t-special{background:#f0e8fb;color:#5a2d82}
.t-sans-solde{background:#f1efe8;color:#7a8f80}

/* ─── ACTION BUTTONS ────────────────────── */
.action-btns{display:flex;gap:5px;flex-wrap:wrap}
.btn-sm{font-size:.72rem;font-weight:500;padding:5px 10px;border-radius:6px;border:1px solid transparent;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:'DM Sans',sans-serif}
.btn-approve{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.btn-refuse{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.btn-edit{background:var(--info-bg);color:var(--info);border-color:var(--info-br)}
.btn-del{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-cancel{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-view{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-secondary{background:var(--white);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .15s}

/* ─── FORMS ─────────────────────────────── */
.form-section{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.form-section h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:600;margin:0 0 1.25rem;color:var(--ink)}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);transition:border-color .15s}
.f-input:focus{border-color:var(--forest);box-shadow:0 0 0 3px rgba(45,90,61,.1);outline:none}
.f-select{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);resize:vertical;min-height:80px}
.f-group{margin-bottom:1rem}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:1.25rem}

/* ─── EMPTY STATE ───────────────────────── */
.empty{padding:2.5rem 1rem;text-align:center;color:var(--muted)}
.empty i{font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3}
.empty p{font-size:.875rem;margin:0}

/* ─── SOLDE BAR ─────────────────────────── */
.solde-card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;margin-bottom:1rem}
.solde-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem}
.solde-type{font-size:.875rem;font-weight:500;color:var(--ink)}
.solde-nums{font-family:'DM Mono',monospace;font-size:.8rem;color:var(--muted)}
.solde-bar{height:6px;background:var(--mint);border-radius:3px;overflow:hidden}
.solde-fill{height:100%;background:var(--forest2);border-radius:3px}
.solde-fill.warn{background:var(--warn)}
.solde-fill.danger{background:var(--danger)}

/* ─── PROFILE ROW ───────────────────────── */
.profile-row{display:flex;align-items:center;gap:12px}
.profile-info .pname{font-weight:500;font-size:.9rem;color:var(--ink)}
.profile-info .pdept{font-size:.75rem;color:var(--muted)}

/* ─── FOOTER ────────────────────────────── */
.footer-app{padding:.75rem 1.75rem;border-top:1px solid var(--border);font-size:.75rem;color:var(--muted);background:var(--white);display:flex;align-items:center;gap:6px}
.footer-app span{color:var(--forest);font-weight:500}
</style>
</head>
<body>

<div class="app-wrap">

    <!-- ═══════════════ SIDEBAR ═══════════════ -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <?php $role = session()->get('role'); ?>

            <?php if ($role === 'admin'): ?>
                <div class="sidebar-logo-icon" style="background:var(--ink);border:1px solid rgba(255,255,255,.15)">
                    <i class="bi bi-shield-check" style="color:var(--leaf)"></i>
                </div>
                <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
            <?php elseif ($role === 'rh'): ?>
                <div class="sidebar-logo-icon">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
            <?php else: ?>
                <div class="sidebar-logo-icon">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
            <?php endif; ?>
        </div>

        <div class="sidebar-section">Menu</div>

        <!-- Nav selon le rôle -->
        <?php if ($role === 'admin'): ?>
        <ul class="sidebar-nav">
            <li><a href="/admin/dashboard" <?= (uri_string() === 'admin/dashboard') ? 'class="active"' : '' ?>><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
            <li><a href="/rh/demandes" <?= str_starts_with(uri_string(), 'rh/') ? 'class="active"' : '' ?>><i class="bi bi-inbox"></i> Toutes les demandes</a></li>
            <li><a href="/admin/employes" <?= str_starts_with(uri_string(), 'admin/employes') ? 'class="active"' : '' ?>><i class="bi bi-people"></i> Employés</a></li>
            <li><a href="/admin/departements" <?= str_starts_with(uri_string(), 'admin/departements') ? 'class="active"' : '' ?>><i class="bi bi-building"></i> Départements</a></li>
            <li><a href="/admin/types-conge" <?= str_starts_with(uri_string(), 'admin/types') ? 'class="active"' : '' ?>><i class="bi bi-tags"></i> Types de congé</a></li>
            <li><a href="/admin/soldes"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
        </ul>

        <?php elseif ($role === 'rh'): ?>
        <ul class="sidebar-nav">
            <li><a href="/rh/dashboard" <?= (uri_string() === 'rh/dashboard') ? 'class="active"' : '' ?>><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="/rh/demandes" <?= (uri_string() === 'rh/demandes') ? 'class="active"' : '' ?>><i class="bi bi-inbox"></i> Demandes à traiter</a></li>
        </ul>

        <?php else: ?>
        <ul class="sidebar-nav">
            <li><a href="/employe/dashboard" <?= (uri_string() === 'employe/dashboard') ? 'class="active"' : '' ?>><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
            <li><a href="/employe/demandes/new"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
            <li><a href="/employe/demandes" <?= (uri_string() === 'employe/demandes') ? 'class="active"' : '' ?>><i class="bi bi-calendar3"></i> Mes demandes</a></li>
            <li><a href="/employe/profil"><i class="bi bi-person"></i> Mon profil</a></li>
        </ul>
        <?php endif; ?>

        <!-- Utilisateur connecté -->
        <div class="sidebar-user">
            <div class="s-user-row">
                <?php
                    $prenom   = session()->get('prenom') ?? '';
                    $nom      = session()->get('nom') ?? '';
                    $initials = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
                ?>
                <div class="avatar av-green"><?= esc($initials) ?></div>
                <div>
                    <div class="user-name"><?= esc($prenom . ' ' . $nom) ?></div>
                    <div class="user-role"><?= esc(session()->get('role')) ?></div>
                </div>
                <a href="/logout" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Déconnexion">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- ═══════════════ MAIN ═══════════════ -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <div class="topbar-title"><?= $this->renderSection('page_title') ?></div>
                <div class="topbar-breadcrumb"><?= $this->renderSection('breadcrumb') ?></div>
            </div>
            <div class="topbar-actions">
                <?= $this->renderSection('topbar_actions') ?>
            </div>
        </div>

        <!-- Flashdata globaux -->
        <div style="padding:0 1.75rem;margin-top:1rem">
            <?php if(session()->getFlashdata('success')): ?>
                <div class="flash flash-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="flash flash-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contenu de la page -->
        <div class="content">
            <?= $this->renderSection('content') ?>
        </div>

        <div class="footer-app">
            <i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4
        </div>
    </div>

</div>
</body>
</html>
