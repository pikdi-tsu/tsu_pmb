<form action="{{ route('admin.system.roles.update', $role->id) }}" method="POST" id="form-role-permission">
    @csrf
    @method('PUT')

    {{-- HEADER --}}
    <div class="modal-header" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); color: #ffffff; padding: 1.15rem 1.5rem;">
        <h5 class="modal-title font-weight-bold d-flex align-items-center" style="font-size: 1.1rem; letter-spacing: -0.01em;">
            <i class="fas fa-user-shield mr-2" style="font-size: 1.2rem; opacity: 0.9;"></i> Edit Role: <b class="ml-1 text-warning">{{ $role->name }}</b>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85; outline: none; text-shadow: none;">
            <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
        </button>
    </div>

    {{-- BODY --}}
    <div class="modal-body p-0" style="height: 70vh; display: flex; flex-direction: column;">

        {{-- INPUT NAMA ROLE --}}
        <div class="bg-white p-3 border-bottom shadow-sm" style="z-index: 10;">
            <div class="form-group mb-0">
                <label class="small text-muted font-weight-bold text-uppercase">Nama Role <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-pen text-primary"></i>
                        </span>
                    </div>
                    <input type="text" name="name" class="form-control font-weight-bold text-dark"
                           value="{{ $role->name }}"
                           placeholder="Contoh: panitia-wawancara" required>
                </div>
            </div>
        </div>

        {{-- SPLIT VIEW (Sidebar + Content) --}}
        <div class="d-flex flex-fill overflow-hidden">
            {{-- SIDEBAR KIRI (Daftar Modul) --}}
            <div class="nav flex-column nav-pills p-2 bg-light border-right overflow-auto"
                 id="v-pills-tab" role="tablist" aria-orientation="vertical" style="width: 32%; min-width: 220px;">

                <div class="px-2 pb-2 mt-2 border-bottom mb-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted text-uppercase font-weight-bold">Daftar Modul</small>
                    <small class="text-muted font-weight-bold">Akses</small>
                </div>

                @foreach($groupedPermissions as $groupName => $permissions)
                    @php
                        $groupSlug = \Illuminate\Support\Str::slug($groupName);
                        $totalCount = count($permissions);
                        $checkedCount = $permissions->filter(function($p) use ($rolePermissions) {
                            return in_array($p->name, $rolePermissions, true);
                        })->count();
                        $hasAccess = $checkedCount > 0;
                    @endphp
                    <a class="nav-link {{ $loop->first ? 'active' : '' }} text-sm mb-1 d-flex align-items-center justify-content-between nav-module-item"
                       id="v-pills-{{ $groupSlug }}-tab"
                       data-toggle="pill"
                       href="#v-pills-{{ $groupSlug }}"
                       role="tab"
                       data-group="{{ $groupSlug }}"
                       data-total="{{ $totalCount }}"
                       aria-controls="v-pills-{{ $groupSlug }}"
                       aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        <div class="d-flex align-items-center text-truncate pr-1">
                            <i class="fas fa-folder mr-2 text-muted icon-folder {{ $hasAccess ? 'd-none' : '' }}" id="folder_icon_{{ $groupSlug }}"></i>
                            <i class="fas fa-check-circle mr-2 text-success icon-check {{ !$hasAccess ? 'd-none' : '' }}" id="check_icon_{{ $groupSlug }}"></i>
                            <span class="font-weight-500 text-truncate module-name">{{ $groupName }}</span>
                        </div>
                        <span class="badge {{ $hasAccess ? 'badge-success' : 'badge-light text-muted border' }} px-2" id="counter_{{ $groupSlug }}" style="font-size: 0.75rem;">
                            {{ $checkedCount }}/{{ $totalCount }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- CONTENT KANAN (Checklist) --}}
            <div class="tab-content p-3 flex-fill overflow-auto bg-white" id="v-pills-tabContent" style="width: 68%;">
                @foreach($groupedPermissions as $groupName => $permissions)
                    @php
                        $groupSlug = \Illuminate\Support\Str::slug($groupName);
                        $totalCount = count($permissions);
                        $checkedCount = $permissions->filter(function($p) use ($rolePermissions) {
                            return in_array($p->name, $rolePermissions, true);
                        })->count();
                        $isAllChecked = ($checkedCount === $totalCount && $totalCount > 0);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="v-pills-{{ $groupSlug }}" role="tabpanel">

                        {{-- Header Group --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white"
                             style="top: -1rem; padding-top: 1rem; margin-top: -1rem;">
                            <h5 class="m-0 text-dark">
                                <i class="fas fa-cube mr-1" style="color: #094b54;"></i> Fitur Modul: <b>{{ $groupName }}</b>
                            </h5>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input check-all-group"
                                       id="check_all_{{ $groupSlug }}"
                                       data-group="{{ $groupSlug }}"
                                    {{ $isAllChecked ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold"
                                       for="check_all_{{ $groupSlug }}" style="cursor: pointer; color: #094b54;">
                                    Pilih Semua
                                </label>
                            </div>
                        </div>

                        {{-- List Checkbox --}}
                        <div class="row">
                            @foreach($permissions as $perm)
                                @php
                                    $isPermChecked = in_array($perm->name, $rolePermissions, true);
                                @endphp
                                <div class="col-md-6 mb-2">
                                    <div class="permission-card p-2 border rounded {{ $isPermChecked ? 'border-green bg-green-light' : '' }}"
                                         id="card_{{ $perm->id }}">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input perm-item group-{{ $groupSlug }}"
                                                   id="perm_{{ $perm->id }}"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   data-group-slug="{{ $groupSlug }}"
                                                   data-perm-id="{{ $perm->id }}"
                                                {{ $isPermChecked ? 'checked' : '' }}>
                                            <label class="custom-control-label d-flex flex-column"
                                                   for="perm_{{ $perm->id }}" style="cursor: pointer;">
                                                <span class="text-dark font-weight-bold text-sm">{{ $perm->name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="modal-footer bg-light justify-content-between">
        <div class="text-muted small">
            <i class="fas fa-info-circle mr-1"></i> Modul bertanda centang (<i class="fas fa-check-circle text-success"></i>) memiliki hak akses aktif.
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold mr-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
            <button type="submit" class="btn btn-sm px-4 font-weight-bold text-white" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border: none; border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</form>

<style>
    .nav-pills .nav-link.active {
        background-color: #094b54 !important;
        color: #fff !important;
    }
    .permission-card {
        transition: all 0.2s;
    }
    .permission-card:hover {
        background-color: #f0fdf4;
        border-color: #094b54 !important;
    }
    .bg-green-light {
        background-color: #f0fdf4;
    }
    .border-green {
        border-color: #094b54 !important;
    }
</style>

<script>
    $(document).ready(function() {
        function updateGroupStatus(groupSlug) {
            var $groupItems = $('.group-' + groupSlug);
            var total = $groupItems.length;
            var checked = $groupItems.filter(':checked').length;

            var $counter = $('#counter_' + groupSlug);
            var $checkIcon = $('#check_icon_' + groupSlug);
            var $folderIcon = $('#folder_icon_' + groupSlug);
            var $checkAllSwitch = $('#check_all_' + groupSlug);

            $counter.text(checked + '/' + total);

            if (checked > 0) {
                $checkIcon.removeClass('d-none');
                $folderIcon.addClass('d-none');
                $counter.removeClass('badge-light text-muted border').addClass('badge-success');
            } else {
                $checkIcon.addClass('d-none');
                $folderIcon.removeClass('d-none');
                $counter.removeClass('badge-success').addClass('badge-light text-muted border');
            }

            $checkAllSwitch.prop('checked', checked === total && total > 0);
        }

        $('.check-all-group').change(function() {
            var groupSlug = $(this).data('group');
            var isChecked = $(this).is(':checked');

            $('.group-' + groupSlug).prop('checked', isChecked).each(function() {
                var permId = $(this).data('perm-id');
                var $card = $('#card_' + permId);
                if (isChecked) {
                    $card.addClass('border-green bg-green-light');
                } else {
                    $card.removeClass('border-green bg-green-light');
                }
            });

            updateGroupStatus(groupSlug);
        });

        $('.perm-item').change(function() {
            var groupSlug = $(this).data('group-slug');
            var permId = $(this).data('perm-id');
            var $card = $('#card_' + permId);

            if ($(this).is(':checked')) {
                $card.addClass('border-green bg-green-light');
            } else {
                $card.removeClass('border-green bg-green-light');
            }

            updateGroupStatus(groupSlug);
        });
    });
</script>
