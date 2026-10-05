<form action="{{ route('admin.system.roles.store') }}" method="POST" id="form-create-role">
    @csrf

    {{-- HEADER --}}
    <div class="modal-header" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); color: #ffffff; padding: 1.15rem 1.5rem;">
        <h5 class="modal-title font-weight-bold d-flex align-items-center" style="font-size: 1.1rem; letter-spacing: -0.01em;">
            <i class="fas fa-plus-circle mr-2" style="font-size: 1.2rem; opacity: 0.9;"></i> Buat Role Lokal Baru
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
                        <span class="input-group-text bg-white"><i class="fas fa-id-badge" style="color: #094b54;"></i></span>
                    </div>
                    <input type="text" name="name" class="form-control font-weight-bold"
                           placeholder="Contoh: panitia-wawancara" required autocomplete="off">
                </div>
                <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle mr-1"></i>
                    Role ini berstatus <b>Lokal (PMB)</b> dan dapat diatur hak akses modulnya secara bebas.
                </small>
            </div>
        </div>

        {{-- SPLIT VIEW (Sidebar + Content) --}}
        <div class="d-flex flex-fill overflow-hidden">
            {{-- SIDEBAR KIRI --}}
            <div class="nav flex-column nav-pills p-2 bg-light border-right overflow-auto"
                 id="v-pills-create-tab" role="tablist" style="width: 32%; min-width: 220px;">

                <div class="px-2 pb-2 mt-2 border-bottom mb-2 d-flex justify-content-between align-items-center">
                    <small class="text-muted text-uppercase font-weight-bold">Daftar Modul</small>
                    <small class="text-muted font-weight-bold">Akses</small>
                </div>

                @foreach($groupedPermissions as $groupName => $permissions)
                    @php
                        $groupSlug = \Illuminate\Support\Str::slug($groupName);
                        $totalCount = count($permissions);
                    @endphp
                    <a class="nav-link {{ $loop->first ? 'active' : '' }} text-sm mb-1 d-flex align-items-center justify-content-between nav-module-create"
                       id="v-create-{{ $groupSlug }}-tab"
                       data-toggle="pill"
                       href="#v-create-{{ $groupSlug }}"
                       role="tab"
                       data-group="{{ $groupSlug }}"
                       data-total="{{ $totalCount }}"
                       aria-controls="v-create-{{ $groupSlug }}"
                       aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        <div class="d-flex align-items-center text-truncate pr-1">
                            <i class="fas fa-folder mr-2 text-muted icon-create-folder" id="create_folder_icon_{{ $groupSlug }}"></i>
                            <i class="fas fa-check-circle mr-2 text-success d-none icon-create-check" id="create_check_icon_{{ $groupSlug }}"></i>
                            <span class="font-weight-500 text-truncate module-name">{{ $groupName }}</span>
                        </div>
                        <span class="badge badge-light border text-muted px-2" id="create_counter_{{ $groupSlug }}" style="font-size: 0.75rem;">
                            0/{{ $totalCount }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- CONTENT KANAN (Checklist) --}}
            <div class="tab-content p-3 flex-fill overflow-auto bg-white" id="v-pills-create-tabContent" style="width: 68%;">
                @foreach($groupedPermissions as $groupName => $permissions)
                    @php
                        $groupSlug = \Illuminate\Support\Str::slug($groupName);
                    @endphp
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="v-create-{{ $groupSlug }}" role="tabpanel">

                        {{-- Header Group --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom sticky-top bg-white"
                             style="top: -1rem; padding-top: 1rem; margin-top: -1rem;">
                            <h5 class="m-0 text-dark">
                                <i class="fas fa-cube mr-1" style="color: #094b54;"></i> Fitur Modul: <b>{{ $groupName }}</b>
                            </h5>

                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input check-all-create"
                                       id="create_check_all_{{ $groupSlug }}"
                                       data-group="{{ $groupSlug }}">
                                <label class="custom-control-label font-weight-bold"
                                       for="create_check_all_{{ $groupSlug }}" style="cursor: pointer; color: #094b54;">
                                    Pilih Semua
                                </label>
                            </div>
                        </div>

                        {{-- List Checkbox --}}
                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-6 mb-2">
                                    <div class="permission-card p-2 border rounded"
                                         id="create_card_{{ $perm->id }}">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input perm-item-create group-create-{{ $groupSlug }}"
                                                   id="create_perm_{{ $perm->id }}"
                                                   name="permissions[]"
                                                   value="{{ $perm->name }}"
                                                   data-group-slug="{{ $groupSlug }}"
                                                   data-perm-id="{{ $perm->id }}">
                                            <label class="custom-control-label d-flex flex-column"
                                                   for="create_perm_{{ $perm->id }}" style="cursor: pointer;">
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
            <i class="fas fa-check-circle text-success mr-1"></i> Tanda centang hijau menunjukkan hak akses aktif.
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold mr-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
            <button type="submit" class="btn btn-sm px-4 font-weight-bold text-white" style="background: linear-gradient(135deg, #094b54 0%, #0c6170 100%); border: none; border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan Role Baru
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
        function updateCreateGroupStatus(groupSlug) {
            var $groupItems = $('.group-create-' + groupSlug);
            var total = $groupItems.length;
            var checked = $groupItems.filter(':checked').length;

            var $counter = $('#create_counter_' + groupSlug);
            var $checkIcon = $('#create_check_icon_' + groupSlug);
            var $folderIcon = $('#create_folder_icon_' + groupSlug);
            var $checkAllSwitch = $('#create_check_all_' + groupSlug);

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

        $('.check-all-create').change(function() {
            var groupSlug = $(this).data('group');
            var isChecked = $(this).is(':checked');

            $('.group-create-' + groupSlug).prop('checked', isChecked).each(function() {
                var permId = $(this).data('perm-id');
                var $card = $('#create_card_' + permId);
                if (isChecked) {
                    $card.addClass('border-green bg-green-light');
                } else {
                    $card.removeClass('border-green bg-green-light');
                }
            });

            updateCreateGroupStatus(groupSlug);
        });

        $('.perm-item-create').change(function() {
            var groupSlug = $(this).data('group-slug');
            var permId = $(this).data('perm-id');
            var $card = $('#create_card_' + permId);

            if ($(this).is(':checked')) {
                $card.addClass('border-green bg-green-light');
            } else {
                $card.removeClass('border-green bg-green-light');
            }

            updateCreateGroupStatus(groupSlug);
        });
    });
</script>
