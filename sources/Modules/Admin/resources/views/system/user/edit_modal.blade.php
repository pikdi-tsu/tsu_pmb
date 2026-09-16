<form action="{{ route('admin.system.users.update', $user->id) }}" method="POST" id="form-update-role">
    @csrf
    @method('PUT')

    <div class="modal-header" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); color: #ffffff; padding: 1.15rem 1.5rem;">
        <h5 class="modal-title font-weight-bold d-flex align-items-center" style="font-size: 1.05rem; letter-spacing: -0.01em;">
            <i class="fas fa-user-tag mr-2" style="font-size: 1.15rem; opacity: 0.9;"></i>
            Atur Role Pengguna
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; outline: none; text-shadow: none;">
            <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
        </button>
    </div>

    <div class="modal-body p-4" style="background: #fdfdfd;">
        {{-- User Info Pill --}}
        <div class="d-flex align-items-center p-3 mb-3 bg-white border rounded" style="border-color: #e2e8f0 !important; gap: 0.85rem;">
            @php
                $url = $user->profile_photo_url;
                $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? $user->username) . '&color=094B54&background=D0EEF2&bold=true';
            @endphp
            <img src="{{ $url }}" onerror="this.onerror=null;this.src='{{ $fallback }}';" class="rounded-circle shadow-sm" style="width: 44px; height: 44px; object-fit: cover; border: 2px solid #cce6e9;" alt="Avatar">
            <div>
                <h6 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $user->name ?? $user->username }}</h6>
                <div class="text-muted" style="font-size: 0.82rem;">
                    <i class="far fa-envelope mr-1"></i>{{ $user->email }} | <i class="fas fa-id-badge mr-1"></i>{{ $user->nik }}
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Pilih Role Akses di Modul PMB <span class="text-danger">*</span>
            </label>
            <select class="form-control select2" name="roles[]" multiple="multiple" data-placeholder="-- Pilih Satu atau Lebih Role --" style="width: 100%;">
                @foreach($allRoles as $role)
                    @php
                        $roleName = $role->name;
                        $isGlobal = $role->is_identity;
                        $label = $roleName . ($isGlobal ? ' [Global Vault]' : ' [Lokal PMB]');
                    @endphp
                    <option value="{{ $roleName }}" {{ in_array($roleName, $userRoles, true) ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted mt-2" style="font-size: 0.78rem;">
                <i class="fas fa-info-circle mr-1 text-info"></i>Pengguna dapat memiliki beberapa role sekaligus untuk mengakses modul-modul terkait.
            </small>
        </div>
    </div>

    <div class="modal-footer bg-light px-4 py-3 border-top" style="border-color: #e2e8f0 !important;">
        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-dismiss="modal" style="border-radius: 8px; font-weight: 600;">
            Batal
        </button>
        <button type="submit" class="btn btn-sm px-4 text-white font-weight-bold" id="btn-save-role" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Perubahan
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2({
                dropdownParent: $('#modal-edit'),
                width: '100%'
            });
        }

        $('#form-update-role').on('submit', function() {
            let btn = $('#btn-save-role');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');
        });
    });
</script>
