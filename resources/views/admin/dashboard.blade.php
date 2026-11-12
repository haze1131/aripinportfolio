<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
    body { background: #0f172a; color: white; }

    header { background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.08); padding: 18px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; }
    header h1 { font-size: 20px; }
    .header-actions { display: flex; gap: 12px; align-items: center; }
    .btn-view { padding: 9px 20px; background: rgba(255,255,255,0.08); border-radius: 8px; color: white; text-decoration: none; font-size: 14px; }
    .btn-logout { padding: 9px 20px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; color: #fca5a5; font-size: 14px; cursor: pointer; border: none; }

    main { max-width: 900px; margin: 40px auto; padding: 0 20px 80px; }

    .alert { border-radius: 10px; padding: 14px 18px; margin-bottom: 25px; font-size: 14px; }
    .alert-success { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); color: #86efac; }
    .alert-error   { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #fca5a5; }

    .card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 28px; margin-bottom: 25px; }
    .card h2 { font-size: 17px; color: #22d3ee; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 12px; margin-bottom: 22px; }

    label { display: block; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 7px; color: #94a3b8; }
    input[type="text"], input[type="email"], textarea, select { width: 100%; padding: 11px 13px; border: 1px solid rgba(255,255,255,0.1); outline: none; border-radius: 9px; background: rgba(255,255,255,0.06); color: white; font-size: 14px; margin-bottom: 16px; }
    textarea { height: 90px; resize: vertical; }
    input[type="file"] { color: #94a3b8; font-size: 13px; margin-bottom: 16px; }
    select option { background: #1e293b; }

    .thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 9px; margin-bottom: 10px; display: block; }

    .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

    .btn { padding: 10px 20px; border: none; border-radius: 8px; font-size: 14px; font-weight: bold; cursor: pointer; }
    .btn-cyan   { background: #22d3ee; color: black; }
    .btn-save   { width: 100%; padding: 14px; border-radius: 10px; background: #22d3ee; color: black; font-size: 15px; font-weight: bold; border: none; cursor: pointer; margin-top: 6px; }
    .btn-delete { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; }
    .btn-edit   { background: rgba(34,211,238,0.1); border: 1px solid rgba(34,211,238,0.2); color: #22d3ee; padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; }

    .item-row { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 18px; margin-bottom: 14px; }
    .item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .item-header strong { font-size: 15px; }
    .item-actions { display: flex; gap: 8px; }
    .item-edit-form { display: none; margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.07); }
    .item-edit-form.open { display: block; }

    .tag-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px; }
    .tag { background: rgba(255,255,255,0.08); padding: 5px 12px; border-radius: 8px; font-size: 13px; color: #cbd5e1; }

    .icon-hint { font-size: 12px; color: #64748b; margin-top: -12px; margin-bottom: 16px; }
    .icon-hint a { color: #22d3ee; }
  </style>
</head>
<body>

<header>
  <h1>Admin Dashboard</h1>
  <div class="header-actions">
    <a href="{{ route('home') }}" class="btn-view">← Lihat Portfolio</a>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
      @csrf
      <button type="submit" class="btn-logout">Logout</button>
    </form>
  </div>
</header>

<main>
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
  @endif

  {{-- ===== GENERAL INFO ===== --}}
  <div class="card">
    <h2>🏠 Home & About</h2>
    <form method="POST" action="{{ route('admin.update') }}" enctype="multipart/form-data">
      @csrf
      <label>Nama</label>
      <input type="text" name="home_name" value="{{ $contents['home_name']->value ?? '' }}">
      <label>Judul / Role</label>
      <input type="text" name="home_title" value="{{ $contents['home_title']->value ?? '' }}">
      <label>Deskripsi Home</label>
      <textarea name="home_description">{{ $contents['home_description']->value ?? '' }}</textarea>
      <label>Deskripsi About</label>
      <textarea name="about_description">{{ $contents['about_description']->value ?? '' }}</textarea>
      <label>Quote / Kutipan</label>
      <textarea name="about_quote">{{ $contents['about_quote']->value ?? '' }}</textarea>
      <label>Foto Home</label>
      @if (!empty($contents['home_photo']->value))
        <img src="{{ asset($contents['home_photo']->value) }}" class="thumb" onerror="this.style.display='none'">
      @endif
      <input type="file" name="home_photo_file" accept="image/jpeg,image/png,image/webp">

      <label>Foto About / Profil</label>
      @php($aboutImage = $contents['about_photo']->value ?? ($contents['about_image']->value ?? null))
      @if ($aboutImage)
        <img src="{{ asset($aboutImage) }}" class="thumb" onerror="this.style.display='none'">
      @endif
      <input type="file" name="about_photo_file" accept="image/jpeg,image/png,image/webp">
      <label>Email Kontak</label>
      <input type="email" name="contact_email" value="{{ $contents['contact_email']->value ?? '' }}">
      <label>Nomor Telepon</label>
      <input type="text" name="contact_phone" value="{{ $contents['contact_phone']->value ?? '' }}">
      <button type="submit" class="btn-save">💾 Simpan</button>
    </form>
  </div>

  {{-- ===== PROJECTS ===== --}}
  <div class="card">
    <h2>💼 Projects</h2>

    @foreach ($projects as $project)
      <div class="item-row">
        <div class="item-header">
          <div style="display:flex; align-items:center; gap:12px;">
            @if ($project->image)
              <img src="{{ asset($project->image) }}" class="thumb" style="margin:0" onerror="this.style.display='none'">
            @endif
            <strong>{{ $project->title }}</strong>
          </div>
          <div class="item-actions">
            <button class="btn-edit" onclick="toggleEdit('project-{{ $project->id }}')">Edit</button>
            <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Hapus project ini?')">
              @csrf
              <button type="submit" class="btn-delete">Hapus</button>
            </form>
          </div>
        </div>
        <p style="color:#94a3b8; font-size:14px;">{{ $project->description }}</p>

        <div class="item-edit-form" id="project-{{ $project->id }}">
          <form method="POST" action="{{ route('projects.update', $project) }}" enctype="multipart/form-data">
            @csrf
            <label>Judul</label>
            <input type="text" name="title" value="{{ $project->title }}">
            <label>Deskripsi</label>
            <textarea name="description">{{ $project->description }}</textarea>
            <label>Gambar (kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" class="btn btn-cyan">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    @endforeach

    <div class="item-row" style="border-style:dashed;">
      <strong style="color:#22d3ee; display:block; margin-bottom:16px;">+ Tambah Project Baru</strong>
      <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Judul</label>
        <input type="text" name="title" placeholder="Nama project">
        <label>Deskripsi</label>
        <textarea name="description" placeholder="Deskripsi singkat project..."></textarea>
        <label>Gambar</label>
        <input type="file" name="image" accept="image/*">
        <button type="submit" class="btn btn-cyan">Tambah Project</button>
      </form>
    </div>
  </div>

  {{-- ===== SKILLS ===== --}}
  <div class="card">
    <h2>🛠 Skills</h2>

    @foreach ($skills as $skill)
      <div class="item-row">
        <div class="item-header">
          <div>
            <strong>{{ $skill->category }}</strong>
            <div class="tag-list">
              @foreach (explode(',', $skill->items) as $item)
                <span class="tag">{{ trim($item) }}</span>
              @endforeach
            </div>
          </div>
          <div class="item-actions">
            <button class="btn-edit" onclick="toggleEdit('skill-{{ $skill->id }}')">Edit</button>
            <form method="POST" action="{{ route('skills.destroy', $skill) }}" onsubmit="return confirm('Hapus skill ini?')">
              @csrf
              <button type="submit" class="btn-delete">Hapus</button>
            </form>
          </div>
        </div>

        <div class="item-edit-form" id="skill-{{ $skill->id }}">
          <form method="POST" action="{{ route('skills.update', $skill) }}">
            @csrf
            <label>Nama Kategori</label>
            <input type="text" name="category" value="{{ $skill->category }}">
            <label>Icon (Remix Icon class)</label>
            <input type="text" name="icon" value="{{ $skill->icon }}" placeholder="ri-code-s-slash-line">
            <p class="icon-hint">Cari icon di <a href="https://remixicon.com" target="_blank">remixicon.com</a>, copy nama class-nya.</p>
            <label>Skills (pisahkan dengan koma)</label>
            <input type="text" name="items" value="{{ $skill->items }}" placeholder="HTML,CSS,JavaScript">
            <button type="submit" class="btn btn-cyan">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    @endforeach

    <div class="item-row" style="border-style:dashed;">
      <strong style="color:#22d3ee; display:block; margin-bottom:16px;">+ Tambah Kategori Skill Baru</strong>
      <form method="POST" action="{{ route('skills.store') }}">
        @csrf
        <label>Nama Kategori</label>
        <input type="text" name="category" placeholder="Frontend">
        <label>Icon (Remix Icon class)</label>
        <input type="text" name="icon" value="ri-tools-line" placeholder="ri-code-s-slash-line">
        <p class="icon-hint">Cari icon di <a href="https://remixicon.com" target="_blank">remixicon.com</a>, copy nama class-nya.</p>
        <label>Skills (pisahkan dengan koma)</label>
        <input type="text" name="items" placeholder="HTML,CSS,JavaScript">
        <button type="submit" class="btn btn-cyan">Tambah Skill</button>
      </form>
    </div>
  </div>
</main>

<script>
function toggleEdit(id) {
  const el = document.getElementById(id);
  el.classList.toggle('open');
}
</script>

</body>
</html>
