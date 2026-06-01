<?php

require_once 'config/db.php';

$filter   = $_GET['filter']   ?? 'all';
$sort     = $_GET['sort']     ?? 'created_at';
$priority = $_GET['priority'] ?? 'all';
$search   = trim($_GET['search'] ?? '');

$allowed_sort = ['created_at', 'due_date', 'priority', 'task'];
if (!in_array($sort, $allowed_sort)) $sort = 'created_at';

$where = ['1=1'];
if ($filter === 'pending')   $where[] = "status = 'pending'";
if ($filter === 'completed') $where[] = "status = 'completed'";
if ($priority !== 'all')     $where[] = "priority = '" . $conn->real_escape_string($priority) . "'";
if ($search !== '')          $where[] = "task LIKE '%" . $conn->real_escape_string($search) . "%'";

$where_sql = implode(' AND ', $where);

$order_sql = $sort === 'priority'
    ? "FIELD(priority,'high','medium','low')"
    : "$sort";

$todos = $conn->query("SELECT * FROM todos WHERE $where_sql ORDER BY $order_sql")->fetch_all(MYSQLI_ASSOC);

$stats = $conn->query("SELECT
    COUNT(*) AS total,
    SUM(status='completed') AS completed,
    SUM(status='pending')   AS pending,
    SUM(priority='high' AND status='pending') AS urgent
FROM todos")->fetch_assoc();

$edit_todo = null;
if (!empty($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $edit_todo = $conn->query("SELECT * FROM todos WHERE id=$eid")->fetch_assoc();
}

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Todo-s</title>

<link rel="stylesheet" href="https:
<link rel="stylesheet" href="https:
<link href="https:

<style>
  :root {
    --bs-primary: #4361ee;
    --bs-primary-rgb: 67,97,238;
    --accent: #4cc9f0;
    --done: #2ec4b6;
    --danger-soft: #ff6b6b;
    --bg: #f0f4ff;
    --card: #ffffff;
    --muted: #8a94a6;
  }

  body {
    background: var(--bg);
    font-family: 'Nunito', sans-serif;
    min-height: 100vh;
  }

  .app-header {
    background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
    color: #fff;
    padding: 2rem 0 3.5rem;
    position: relative;
    overflow: hidden;
  }
  .app-header::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http:
  }
  .app-title { font-size: 2rem; font-weight: 800; letter-spacing: -0.5px; }

  .stat-card {
    border: none; border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    transition: transform .2s;
  }
  .stat-card:hover { transform: translateY(-3px); }

  .main-card {
    border: none; border-radius: 20px;
    box-shadow: 0 8px 32px rgba(67,97,238,.10);
    margin-top: -2rem;
  }

  .add-form-input {
    border: 2px solid #e8ecf4;
    border-radius: 12px 0 0 12px;
    padding: .75rem 1rem;
    font-size: .95rem;
    transition: border-color .2s;
  }
  .add-form-input:focus {
    border-color: var(--bs-primary);
    box-shadow: none;
  }
  .btn-add {
    background: var(--bs-primary);
    color: #fff; font-weight: 700;
    border-radius: 0 12px 12px 0;
    padding: .75rem 1.4rem;
    border: 2px solid var(--bs-primary);
    transition: background .2s;
  }
  .btn-add:hover { background: #3a0ca3; border-color: #3a0ca3; color: #fff; }

  .todo-item {
    border: none;
    border-bottom: 1px solid #eef1f8;
    border-radius: 12px !important;
    margin-bottom: .5rem;
    padding: .85rem 1rem;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .15s;
  }
  .todo-item:hover { box-shadow: 0 4px 16px rgba(67,97,238,.12); transform: translateX(3px); }
  .todo-item.done { background: #f8fff9; }
  .todo-item.done .todo-text { text-decoration: line-through; color: var(--muted); }

  .todo-check {
    width: 20px; height: 20px;
    border: 2px solid #c5cfe8;
    border-radius: 6px;
    cursor: pointer;
    accent-color: var(--done);
  }

  .badge-priority {
    font-size: .7rem; font-weight: 700;
    padding: .25em .6em; border-radius: 6px;
    text-transform: uppercase; letter-spacing: .5px;
  }
  .priority-high   { background: #ffe5e5; color: #d63031; }
  .priority-medium { background: #fff3cd; color: #d68910; }
  .priority-low    { background: #e0f7fa; color: #00838f; }

  .btn-icon {
    width: 32px; height: 32px;
    display: inline-flex; align-items: center; justify-content: center;
    border-radius: 8px; border: none;
    font-size: .85rem; cursor: pointer;
    transition: background .15s, transform .1s;
  }
  .btn-icon:hover { transform: scale(1.1); }
  .btn-edit   { background: #e8f4fd; color: #2980b9; }
  .btn-edit:hover { background: #cce5ff; }
  .btn-delete { background: #fdecea; color: var(--danger-soft); }
  .btn-delete:hover { background: #ffc9c9; }

  .empty-state { padding: 3rem 1rem; color: var(--muted); }
  .empty-state i { font-size: 3rem; opacity: .3; }

  .progress { border-radius: 20px; height: 8px; }
  .progress-bar { border-radius: 20px; background: linear-gradient(90deg, #4cc9f0, #4361ee); }

  .nav-filter .nav-link {
    color: var(--muted); font-weight: 600; font-size: .88rem;
    border-radius: 10px; padding: .4rem 1rem;
    border: none; background: none;
  }
  .nav-filter .nav-link.active {
    background: #eef1ff; color: var(--bs-primary);
  }

  .due-badge {
    font-size: .72rem; background: #fff3cd;
    color: #8a6d3b; border-radius: 6px;
    padding: .2em .55em; font-weight: 600;
  }
  .due-badge.overdue { background: #fdecea; color: #c0392b; }
  .due-badge.today   { background: #d4edda; color: #155724; }
</style>
</head>
<body>

<div class="app-header">
  <div class="container">
    <div class="d-flex align-items-center gap-2 mb-4">
      <i class="bi bi-check2-square fs-3 text-white"></i>
      <h1 class="app-title mb-0">My Todo-s</h1>
    </div>

    <div class="row g-3">
      <div class="col-6 col-md-3">
        <div class="stat-card card text-center p-3">
          <div class="fw-800 fs-3 text-primary"><?= $stats['total'] ?></div>
          <div class="small text-muted">Total Tasks</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card text-center p-3">
          <div class="fw-800 fs-3 text-success"><?= $stats['completed'] ?></div>
          <div class="small text-muted">Selesai</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card text-center p-3">
          <div class="fw-800 fs-3 text-warning"><?= $stats['pending'] ?></div>
          <div class="small text-muted">Pending</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card card text-center p-3">
          <div class="fw-800 fs-3 text-danger"><?= $stats['urgent'] ?></div>
          <div class="small text-muted">Urgent</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container pb-5">
  <div class="main-card card p-4">

    <?php if ($success): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form action="actions.php" method="POST" class="mb-4">
      <input type="hidden" name="action" value="add">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-5">
          <input type="text" name="task" class="form-control add-form-input rounded-3"
                 placeholder="Tambah task baru..." required>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label small fw-600 mb-1">Due Date</label>
          <input type="date" name="due_date" class="form-control rounded-3">
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-600 mb-1">Prioritas</label>
          <select name="priority" class="form-select rounded-3">
            <option value="low">🟢 Rendah</option>
            <option value="medium" selected>🟡 Sedang</option>
            <option value="high">🔴 Tinggi</option>
          </select>
        </div>
        <div class="col-12 col-md-2">
          <button type="submit" class="btn btn-add w-100 rounded-3">
            <i class="bi bi-plus-lg me-1"></i> ADD
          </button>
        </div>
      </div>
    </form>

    <?php if ($stats['total'] > 0): ?>
      <?php $pct = round(($stats['completed'] / $stats['total']) * 100); ?>
      <div class="mb-4">
        <div class="d-flex justify-content-between small fw-600 mb-1">
          <span>Progress</span>
          <span><?= $pct ?>%</span>
        </div>
        <div class="progress">
          <div class="progress-bar" style="width:<?= $pct ?>%"></div>
        </div>
      </div>
    <?php endif; ?>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">

      <ul class="nav nav-filter gap-1 mb-0 flex-wrap">
        <?php foreach (['all'=>'Semua','pending'=>'Pending','completed'=>'Selesai'] as $f=>$label): ?>
          <li class="nav-item">
            <a class="nav-link <?= $filter===$f?'active':'' ?>"
               href="?filter=<?= $f ?>&sort=<?= $sort ?>&priority=<?= $priority ?>&search=<?= urlencode($search) ?>">
               <?= $label ?>
               <?php if($f==='all') echo '<span class="badge bg-primary ms-1">'.$stats['total'].'</span>'; ?>
               <?php if($f==='pending') echo '<span class="badge bg-warning text-dark ms-1">'.$stats['pending'].'</span>'; ?>
               <?php if($f==='completed') echo '<span class="badge bg-success ms-1">'.$stats['completed'].'</span>'; ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
        <input type="text" name="search" class="form-control form-control-sm rounded-3"
               style="width:160px" placeholder="🔍 Cari task..." value="<?= htmlspecialchars($search) ?>">
        <select name="priority" class="form-select form-select-sm rounded-3" style="width:130px">
          <option value="all" <?= $priority==='all'?'selected':'' ?>>Semua prioritas</option>
          <option value="high"   <?= $priority==='high'?'selected':'' ?>>🔴 Tinggi</option>
          <option value="medium" <?= $priority==='medium'?'selected':'' ?>>🟡 Sedang</option>
          <option value="low"    <?= $priority==='low'?'selected':'' ?>>🟢 Rendah</option>
        </select>
        <select name="sort" class="form-select form-select-sm rounded-3" style="width:150px" onchange="this.form.submit()">
          <option value="created_at" <?= $sort==='created_at'?'selected':'' ?>>Tanggal ditambah</option>
          <option value="due_date"   <?= $sort==='due_date'?'selected':'' ?>>Due date</option>
          <option value="priority"   <?= $sort==='priority'?'selected':'' ?>>Prioritas</option>
          <option value="task"       <?= $sort==='task'?'selected':'' ?>>Nama (A-Z)</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm rounded-3">Terapkan</button>
      </form>
    </div>

    <?php if (empty($todos)): ?>
      <div class="empty-state text-center">
        <i class="bi bi-inbox d-block mb-2"></i>
        <p class="mb-0">Tidak ada task ditemukan</p>
      </div>
    <?php else: ?>
      <div class="todo-list">
        <?php foreach ($todos as $t):
          $is_done  = $t['status'] === 'completed';
          $due_class = '';
          $due_label = '';
          if ($t['due_date']) {
            $due_ts  = strtotime($t['due_date']);
            $today   = strtotime(date('Y-m-d'));
            $due_fmt = date('d M Y', $due_ts);
            if ($due_ts < $today && !$is_done) { $due_class='overdue'; $due_label="⚠ $due_fmt"; }
            elseif ($due_ts === $today)         { $due_class='today';   $due_label="📅 Hari ini"; }
            else                                { $due_label="📅 $due_fmt"; }
          }
        ?>
        <div class="todo-item d-flex align-items-center gap-3 <?= $is_done?'done':'' ?>">
          <a href="actions.php?action=toggle&id=<?= $t['id'] ?>" title="Toggle selesai">
            <i class="bi <?= $is_done?'bi-check-square-fill text-success':'bi-square text-secondary' ?> fs-5"></i>
          </a>

          <div class="flex-grow-1">
            <span class="todo-text fw-600"><?= htmlspecialchars($t['task']) ?></span>
            <div class="d-flex flex-wrap gap-2 mt-1">
              <span class="badge-priority priority-<?= $t['priority'] ?>">
                <?= $t['priority'] === 'high' ? '🔴' : ($t['priority']==='medium'?'🟡':'🟢') ?>
                <?= ucfirst($t['priority']) ?>
              </span>
              <?php if ($due_label): ?>
                <span class="due-badge <?= $due_class ?>"><?= $due_label ?></span>
              <?php endif; ?>
              <span class="text-muted" style="font-size:.72rem">
                <i class="bi bi-clock me-1"></i><?= date('d M Y H:i', strtotime($t['created_at'])) ?>
              </span>
            </div>
          </div>

          <div class="d-flex gap-1 flex-shrink-0">
            <a href="?edit_id=<?= $t['id'] ?>&filter=<?= $filter ?>&sort=<?= $sort ?>"
               class="btn-icon btn-edit" title="Edit">
              <i class="bi bi-pencil-fill"></i>
            </a>
            <a href="actions.php?action=delete&id=<?= $t['id'] ?>"
               class="btn-icon btn-delete" title="Hapus"
               onclick="return confirm('Yakin hapus task ini?')">
              <i class="bi bi-trash-fill"></i>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php if ($stats['completed'] > 0): ?>
        <div class="text-end mt-3">
          <a href="actions.php?action=clear_completed"
             class="btn btn-outline-danger btn-sm rounded-3"
             onclick="return confirm('Hapus semua task selesai?')">
            <i class="bi bi-trash me-1"></i> Hapus semua yang selesai
          </a>
        </div>
      <?php endif; ?>
    <?php endif; ?>

  </div></div>
<?php if ($edit_todo): ?>
<div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.45)">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-700">
          <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Task
        </h5>
        <a href="index.php" class="btn-close"></a>
      </div>
      <form action="actions.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" name="id" value="<?= $edit_todo['id'] ?>">

          <div class="mb-3">
            <label class="form-label fw-600">Task</label>
            <input type="text" name="task" class="form-control rounded-3"
                   value="<?= htmlspecialchars($edit_todo['task']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Due Date</label>
            <input type="date" name="due_date" class="form-control rounded-3"
                   value="<?= $edit_todo['due_date'] ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-600">Prioritas</label>
            <select name="priority" class="form-select rounded-3">
              <option value="low"    <?= $edit_todo['priority']==='low'?'selected':''    ?>>🟢 Rendah</option>
              <option value="medium" <?= $edit_todo['priority']==='medium'?'selected':'' ?>>🟡 Sedang</option>
              <option value="high"   <?= $edit_todo['priority']==='high'?'selected':''   ?>>🔴 Tinggi</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <a href="index.php" class="btn btn-light rounded-3">Batal</a>
          <button type="submit" class="btn btn-primary rounded-3 px-4 fw-700">
            <i class="bi bi-save me-1"></i>Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="https:
</body>
</html>