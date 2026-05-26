<!-- ══ MODAL LOGOUT ══ -->
<div class="modal fade modal-logout" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <div class="logout-icon-wrap">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h5 style="font-family:'Poppins',sans-serif;font-weight:800;color:var(--secondary);margin-bottom:.75rem;font-size:1.45rem">Konfirmasi Logout</h5>
                <p style="font-size:.95rem;color:var(--muted);line-height:1.6;margin-bottom:2.2rem">
                    Apakah kamu yakin ingin keluar dari akun <strong style="color:var(--secondary)">{{ auth()->user()->name ?? 'Ahmad Firdaus' }}</strong>?
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn-logout-cancel flex-grow-1" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-logout-confirm flex-grow-1 d-flex align-items-center justify-content-center gap-2" onclick="doLogout()">
                        <i class="bi bi-box-arrow-right"></i> Ya, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
