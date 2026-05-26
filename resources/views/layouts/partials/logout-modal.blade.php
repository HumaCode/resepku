<!-- ══ MODAL LOGOUT ══ -->
<div class="modal fade modal-logout" id="logoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="logout-icon-wrap">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h5 style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--secondary);margin-bottom:.4rem">Konfirmasi Logout</h5>
                <p style="font-size:.83rem;color:var(--muted);line-height:1.6;margin-bottom:1.4rem">
                    Apakah kamu yakin ingin keluar dari akun <strong style="color:var(--secondary)">{{ auth()->user()->name ?? 'Ahmad Firdaus' }}</strong>?
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn-logout-cancel" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-logout-confirm" onclick="doLogout()">
                        <i class="bi bi-box-arrow-right me-1"></i> Ya, Logout
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
