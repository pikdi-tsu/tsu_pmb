<div class="modal-header text-white" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); padding: 1.1rem 1.4rem;">
    <h5 class="modal-title font-weight-bold" style="font-size: 1.05rem;">
        <i class="fas fa-edit mr-2 text-warning"></i> Edit Menu: {{ $menu->name }}
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form action="{{ route('admin.system.menus.update', $menu->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-body p-4">
        <div class="form-group mb-3">
            <label class="font-weight-bold text-sm text-dark">Nama Menu <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required style="border-radius: 8px;">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-sm text-dark">Icon Class (FontAwesome)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; background: #f8fafc;">
                                <i class="{{ $menu->icon ?: 'fas fa-box' }}" style="color: #094b54;"></i>
                            </span>
                        </div>
                        <input type="text" name="icon" class="form-control" value="{{ $menu->icon }}" placeholder="Contoh: fas fa-box" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-sm text-dark">Route Laravel</label>
                    <input type="text" name="route" id="edit_route" class="form-control" value="{{ $menu->route }}" placeholder="admin.databeasiswa.show" style="border-radius: 8px;">
                    <small class="text-muted d-block mt-1">Isi <code>#</code> atau kosongkan jika berupa Menu Induk (Dropdown).</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-sm text-dark">Permission Kunci</label>
                    <select name="permission_name" id="edit_permission" class="form-control select2-edit" style="width: 100%;">
                        <option value="">-- Public (Bebas Akses) --</option>
                        @foreach($permissions as $perm)
                            <option value="{{ $perm }}" {{ $menu->permission_name === $perm ? 'selected' : '' }}>
                                {{ $perm }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label class="font-weight-bold text-sm text-dark">Parent Menu (Induk)</label>
                    <select name="parent_id" id="edit_parent" class="form-control select2-edit" style="width: 100%;">
                        <option value="">-- Jadikan Menu Utama (Root) --</option>
                        @foreach($parents as $id => $name)
                            <option value="{{ $id }}" {{ $menu->parent_id === $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-sm text-dark">Urutan Tampil (Order)</label>
                    <input type="number" name="order" class="form-control" value="{{ $menu->order }}" style="border-radius: 8px;">
                    <small class="text-muted">Angka lebih kecil tampil lebih atas.</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label class="font-weight-bold text-sm text-dark">Status Visibilitas</label>
                    <div class="custom-control custom-switch pt-2">
                        <input type="checkbox" class="custom-control-input" id="edit_isactive" name="isactive" value="1" {{ $menu->isactive ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-sm text-success" for="edit_isactive">Aktif (Tampil di Sidebar)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer justify-content-between p-3" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
        <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">
            <i class="fas fa-times mr-1"></i> Batal
        </button>
        <button type="submit" class="btn text-white px-4 font-weight-bold" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Update Perubahan
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2-edit').select2({
                dropdownParent: $('#modal-edit'),
                width: '100%'
            });
        }
    });
</script>
