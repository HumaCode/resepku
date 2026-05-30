<x-app-layout>
    @section('title', 'Detail Resep - ' . $recipe->title)
    @section('page-title', 'Detail Resep')

    @push('styles')
        @vite(['resources/css/backend/resep-detail.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/backend/resep-detail.js'])
    @endpush

    <div id="recipeDetailContainer" data-id="{{ $recipe->id }}">
        <!-- Breadcrumb -->
        <x-breadcrumb-bar 
            title="Detail Resep"
            icon="bi-eye"
            desc="Informasi lengkap resep beserta data komentar dan statistik."
            :items="[
                'Home' => route('dashboard'),
                'Resep' => route('recipes.index'),
                'Detail' => null
            ]"
        />

        <!-- Action Bar -->
        <div class="detail-action-bar" data-aos="fade-up" data-aos-delay="50">
            <!-- Status Badge -->
            <span class="ab-status ab-status-badge {{ $recipe->status }}">
                @if($recipe->status === 'published')
                    <i class="bi bi-check-circle-fill"></i> Published
                @elseif($recipe->status === 'draft')
                    <i class="bi bi-hourglass-split"></i> Draft
                @elseif($recipe->status === 'pending')
                    <i class="bi bi-hourglass-split"></i> Pending
                @else
                    <i class="bi bi-x-circle-fill"></i> Rejected
                @endif
            </span>

            <!-- Featured Badge -->
            <span class="ab-status ab-featured-badge {{ $recipe->is_featured === '1' ? 'd-flex' : 'd-none' }}" 
                  style="background:rgba(245,200,66,.12);color:#b45309;border-color:rgba(245,200,66,.3)">
                <i class="bi bi-star-fill"></i> Unggulan
            </span>

            <div style="height:22px;width:1px;background:var(--border);margin:0 .1rem" class="d-none d-md-block"></div>

            <!-- Edit Button -->
            <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn-action primary">
                <i class="bi bi-pencil-square"></i>
                <span class="d-none d-sm-inline">Edit Resep</span>
                <span class="d-inline d-sm-none">Edit</span>
            </a>

            <!-- Toggle Publish / Unpublish Button -->
            @if($recipe->status === 'published')
                <button class="btn-action warning" onclick="toggleStatus(this)">
                    <i class="bi bi-eye-slash"></i>
                    <span class="d-none d-md-inline">Unpublish</span>
                </button>
            @else
                <button class="btn-action success" onclick="toggleStatus(this)">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="d-none d-md-inline">Publish</span>
                </button>
            @endif

            <!-- Duplicate Button -->
            <button class="btn-action outline d-none d-md-flex" onclick="duplicateResep(this)">
                <i class="bi bi-copy"></i> Duplikat
            </button>

            <!-- Delete Button -->
            <button class="btn-action danger ms-auto" data-bs-toggle="modal" data-bs-target="#modalHapus">
                <i class="bi bi-trash3"></i>
                <span class="d-none d-sm-inline">Hapus</span>
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="stat-cards" data-aos="fade-up" data-aos-delay="80">
            <div class="stat-card">
                <div class="stat-card-icon orange"><i class="bi bi-eye-fill"></i></div>
                <div class="stat-card-value">{{ number_format($recipe->views) }}</div>
                <div class="stat-card-label">Total Dilihat</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon green"><i class="bi bi-bookmark-fill"></i></div>
                <div class="stat-card-value">-</div>
                <div class="stat-card-label">Disimpan</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon blue"><i class="bi bi-chat-dots-fill"></i></div>
                <div class="stat-card-value">0</div>
                <div class="stat-card-label">Komentar</div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon yellow"><i class="bi bi-star-fill"></i></div>
                <div class="stat-card-value">{{ number_format($recipe->rating, 1) }}</div>
                <div class="stat-card-label">Rata-rata Rating</div>
            </div>
        </div>

        <!-- 2-Kolom Layout -->
        <div class="detail-layout">
            <!-- Kolom Utama (Kiri) -->
            <div class="detail-main">
                <!-- Hero image and title -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="detail-card-body">
                        <div class="hero-img-wrap">
                            @if($recipe->getFirstMediaUrl('cover'))
                                <img src="{{ $recipe->getFirstMediaUrl('cover') }}" alt="{{ $recipe->title }}">
                            @else
                                <div style="width:100%;height:360px;background:linear-gradient(135deg,#8b3a10 0%,#c4501c 35%,#e85d26 60%,#f5a623 100%);display:flex;align-items:center;justify-content:center;font-size:6rem;">
                                    🍖
                                </div>
                            @endif
                            <div class="hero-overlay">
                                <span class="hero-category-badge"><i class="bi bi-tag-fill"></i> {{ $recipe->category->name ?? 'Uncategorized' }}</span>
                                <div class="hero-title">{{ $recipe->title }}</div>
                                <div class="hero-meta">
                                    <div class="hero-meta-item"><i class="bi bi-clock-fill"></i> {{ $recipe->prep_time + $recipe->cook_time }} menit</div>
                                    <div class="hero-meta-sep"></div>
                                    <div class="hero-meta-item"><i class="bi bi-people-fill"></i> {{ $recipe->servings }} porsi</div>
                                    <div class="hero-meta-sep"></div>
                                    <div class="hero-meta-item"><i class="bi bi-speedometer2"></i> {{ ucfirst($recipe->difficulty) }}</div>
                                    <div class="hero-meta-sep"></div>
                                    <div class="hero-meta-item"><i class="bi bi-star-fill"></i> {{ number_format($recipe->rating, 1) }} (0)</div>
                                </div>
                            </div>
                        </div>

                        <div class="resep-title-display">{{ $recipe->title }}</div>
                        <p class="resep-desc-display">{{ $recipe->description }}</p>

                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="slug-display"><i class="bi bi-link-45deg"></i>{{ $recipe->slug }}</span>
                        </div>

                        @if($recipe->tags->isNotEmpty())
                            <div class="mt-3">
                                <div class="tag-chips">
                                    @foreach($recipe->tags as $tag)
                                        <span class="tag-chip-display"><i class="bi bi-hash"></i>{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Video Tutorials -->
                @if($recipe->videos->isNotEmpty())
                    <div class="detail-card" data-aos="fade-up" data-aos-delay="110">
                        <div class="detail-card-header">
                            <div class="dc-icon"><i class="bi bi-play-btn-fill"></i></div>
                            <div>
                                <div class="dc-title">Video Tutorial</div>
                                <div class="dc-sub">Tonton panduan langkah demi langkah</div>
                            </div>
                        </div>
                        <div class="detail-card-body">
                            <div class="row g-3">
                                @foreach($recipe->videos->sortBy('orders') as $video)
                                    <div class="col-12 {{ $recipe->videos->count() > 1 ? 'col-md-6' : 'col-md-12' }}">
                                        @if($video->video_provider === 'youtube')
                                            @php
                                                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|win/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->video_url, $match);
                                                $youtubeId = $match[1] ?? null;
                                            @endphp
                                            <div class="video-container-youtube mb-2">
                                                @if($youtubeId)
                                                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" allowfullscreen></iframe>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center" style="position: absolute; top:0; left:0; width:100%; height:100%; background:var(--bg)">
                                                        <a href="{{ $video->video_url }}" target="_blank" class="text-primary font-weight-bold"><i class="bi bi-play-fill"></i> Tonton di YouTube</a>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($video->video_provider === 'tiktok')
                                            @php
                                                preg_match('/(?:tiktok\.com\/@[^\/]+\/video\/|tiktok\.com\/v\/)(\d+)/i', $video->video_url, $match);
                                                $tiktokId = $match[1] ?? null;
                                                $tiktokCleanUrl = $tiktokId ? "https://www.tiktok.com/@video/video/{$tiktokId}" : $video->video_url;
                                            @endphp
                                            @if($tiktokId)
                                                <div class="d-flex justify-content-center w-100 mb-2">
                                                    <blockquote class="tiktok-embed" cite="{{ $tiktokCleanUrl }}" data-video-id="{{ $tiktokId }}" style="max-width: 605px; min-width: 325px; width: 100%;">
                                                        <section>
                                                            <a target="_blank" href="{{ $tiktokCleanUrl }}">Video Tutorial</a>
                                                        </section>
                                                    </blockquote>
                                                </div>
                                            @else
                                                <div class="video-container-youtube mb-2">
                                                    <div class="d-flex flex-column align-items-center justify-content-center text-center p-3" style="position: absolute; top:0; left:0; width:100%; height:100%; background:var(--bg)">
                                                        <i class="bi bi-tiktok" style="font-size: 2rem; color: var(--secondary)"></i>
                                                        <a href="{{ $video->video_url }}" target="_blank" class="btn btn-sm btn-outline-dark mt-2">Tonton di TikTok</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($video->video_provider === 'instagram')
                                            @php
                                                preg_match('/(?:instagram\.com\/(?:p|reel|tv)\/)([^\\/?#&]+)/i', $video->video_url, $match);
                                                $instagramId = $match[1] ?? null;
                                                $isReel = str_contains($video->video_url, '/reel/');
                                                $instagramCleanUrl = $instagramId 
                                                    ? ($isReel ? "https://www.instagram.com/reel/{$instagramId}/" : "https://www.instagram.com/p/{$instagramId}/") 
                                                    : $video->video_url;
                                            @endphp
                                            @if($instagramId)
                                                <div class="d-flex justify-content-center w-100 mb-2">
                                                    <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="{{ $instagramCleanUrl }}" data-instgrm-version="14" style="background:#FFF; border:0; border-radius:12px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;">
                                                        <div style="padding:16px;">
                                                            <a href="{{ $instagramCleanUrl }}" style="background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank">
                                                                View this post on Instagram
                                                            </a>
                                                        </div>
                                                    </blockquote>
                                                </div>
                                            @else
                                                <div class="video-container-youtube mb-2">
                                                    <div class="d-flex flex-column align-items-center justify-content-center text-center p-3" style="position: absolute; top:0; left:0; width:100%; height:100%; background:var(--bg)">
                                                        <i class="bi bi-instagram" style="font-size: 2rem; color: #e1306c"></i>
                                                        <a href="{{ $video->video_url }}" target="_blank" class="btn btn-sm btn-outline-danger mt-2">Tonton di Instagram</a>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <div class="video-container-youtube mb-2">
                                                <div class="d-flex flex-column align-items-center justify-content-center text-center p-3" style="position: absolute; top:0; left:0; width:100%; height:100%; background:var(--bg)">
                                                    <i class="bi bi-play-circle" style="font-size: 2rem; color: var(--primary)"></i>
                                                    <a href="{{ $video->video_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Tonton Video</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Konten Lengkap -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="120">
                    <div class="detail-card-header">
                        <div class="dc-icon"><i class="bi bi-file-richtext"></i></div>
                        <div>
                            <div class="dc-title">Konten Resep</div>
                            <div class="dc-sub">Deskripsi lengkap dan tips memasak</div>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        <div class="resep-content">
                            {!! $recipe->content !!}
                        </div>
                    </div>
                </div>

                <!-- Bahan-bahan -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="130">
                    <div class="detail-card-header">
                        <div class="dc-icon"><i class="bi bi-basket2"></i></div>
                        <div>
                            <div class="dc-title">Bahan-Bahan</div>
                            <div class="dc-sub">{{ $recipe->ingredients->count() }} bahan untuk {{ $recipe->servings }} porsi</div>
                        </div>
                    </div>
                    <div class="detail-card-body" style="padding: 1.5rem">
                        <div class="ingredients-interactive-grid">
                             @forelse($recipe->ingredients as $ingredient)
                                    @php
                                        $master = $ingredient->masterIngredient;
                                        $emoji = $master ? $master->emoji : '🥦';
                                        $description = $master ? $master->description : null;
                                    @endphp
                                    <div class="ingredient-interactive-card">
                                        <div class="ing-icon-zone">
                                            <span class="ing-emoji">{{ $emoji }}</span>
                                        </div>
                                        <div class="ing-info-zone">
                                            <div class="ing-title-text">{{ $ingredient->name }}</div>
                                            @if($description)
                                                <div class="ing-notes-text" title="{{ $description }}">{{ $description }}</div>
                                            @endif
                                        </div>
                                        <div class="ing-qty-badge">
                                            <span class="qty-num">{{ $ingredient->amount }}</span>
                                            <span class="qty-unit">{{ $ingredient->unit }}</span>
                                        </div>
                                    </div>
                            @empty
                                <div class="text-center text-muted py-3 w-100">Tidak ada bahan yang ditambahkan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Langkah Memasak -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="140">
                    <div class="detail-card-header">
                        <div class="dc-icon"><i class="bi bi-list-ol"></i></div>
                        <div>
                            <div class="dc-title">Langkah Memasak</div>
                            <div class="dc-sub">{{ $recipe->steps->count() }} langkah cara pembuatan</div>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        <div class="steps-list">
                            @forelse($recipe->steps->sortBy('step_number') as $step)
                                <div class="step-card {{ $step->image ? 'step-card-has-image' : '' }}">
                                    <div class="step-number">{{ $step->step_number }}</div>
                                    <div class="step-body">
                                        <div class="step-text">{{ $step->description }}</div>
                                        @if($step->image)
                                            <div class="step-img-wrap">
                                                <img src="{{ asset('storage/' . $step->image) }}"
                                                     alt="Langkah {{ $step->step_number }}"
                                                     class="step-img"
                                                     loading="lazy">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-3">Tidak ada langkah memasak yang ditambahkan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Informasi Nutrisi -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="detail-card-header">
                        <div class="dc-icon"><i class="bi bi-heart-pulse"></i></div>
                        <div>
                            <div class="dc-title">Informasi Nutrisi</div>
                            <div class="dc-sub">Per satu porsi sajian</div>
                        </div>
                    </div>
                    <div class="detail-card-body">
                        <div class="nutrition-grid-display">
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->calories ?? '-' }}</div>
                                <div class="np-unit">kkal</div>
                                <div class="np-label">Kalori</div>
                            </div>
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->protein ?? '-' }}</div>
                                <div class="np-unit">g</div>
                                <div class="np-label">Protein</div>
                            </div>
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->fat ?? '-' }}</div>
                                <div class="np-unit">g</div>
                                <div class="np-label">Lemak</div>
                            </div>
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->carbs ?? '-' }}</div>
                                <div class="np-unit">g</div>
                                <div class="np-label">Karbohidrat</div>
                            </div>
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->fiber ?? '-' }}</div>
                                <div class="np-unit">g</div>
                                <div class="np-label">Serat</div>
                            </div>
                            <div class="nutrition-pill">
                                <div class="np-value">{{ $recipe->sugar ?? '-' }}</div>
                                <div class="np-unit">g</div>
                                <div class="np-label">Gula</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Komentar (Static Placeholder / Simulated) -->
                <div class="detail-card" data-aos="fade-up" data-aos-delay="160">
                    <div class="detail-card-header">
                        <div class="dc-icon"><i class="bi bi-chat-dots"></i></div>
                        <div>
                            <div class="dc-title">Komentar</div>
                            <div class="dc-sub">0 komentar — Moderasi dinonaktifkan</div>
                        </div>
                    </div>
                    <div class="detail-card-body text-center py-4">
                        <span class="text-muted">Fitur komentar pada resep ini belum memiliki data.</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Sidebar (Kanan) -->
            <div class="detail-aside" data-aos="fade-left" data-aos-delay="120">
                <!-- Aksi Cepat -->
                <div class="aside-card">
                    <div class="aside-card-header">
                        <div class="aside-icon"><i class="bi bi-lightning-fill"></i></div>
                        <div class="aside-title">Aksi Cepat</div>
                    </div>
                    <div class="aside-card-body">
                        <div class="aside-actions">
                            <a href="{{ route('recipes.edit', $recipe->id) }}" class="aside-btn edit">
                                <i class="bi bi-pencil-square"></i> Edit Resep
                            </a>
                            <button class="aside-btn feature" onclick="toggleFeatured(this)">
                                @if($recipe->is_featured === '1')
                                    <i class="bi bi-star-fill"></i> Lepas dari Unggulan
                                @else
                                    <i class="bi bi-star"></i> Tandai Unggulan
                                @endif
                            </button>
                            <button class="aside-btn unpublish" onclick="toggleStatus(this)">
                                @if($recipe->status === 'published')
                                    <i class="bi bi-eye-slash"></i> Unpublish
                                @else
                                    <i class="bi bi-check-circle-fill"></i> Publish
                                @endif
                            </button>
                            <button class="aside-btn" style="color:var(--muted)" onclick="duplicateResep(this)">
                                <i class="bi bi-copy"></i> Duplikat Resep
                            </button>
                            <button class="aside-btn delete" data-bs-toggle="modal" data-bs-target="#modalHapus">
                                <i class="bi bi-trash3"></i> Hapus Permanen
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="aside-card">
                    <div class="aside-card-header">
                        <div class="aside-icon"><i class="bi bi-info-circle"></i></div>
                        <div class="aside-title">Metadata</div>
                    </div>
                    <div class="aside-card-body">
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-person-fill"></i> Penulis</span>
                            <span class="meta-val">{{ $recipe->user->name ?? 'System' }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-calendar-plus"></i> Dibuat</span>
                            <span class="meta-val">{{ $recipe->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-calendar-check"></i> Diupdate</span>
                            <span class="meta-val">{{ $recipe->updated_at->format('d M Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-send-fill"></i> Dipublish</span>
                            <span class="meta-val">{{ $recipe->status === 'published' ? $recipe->updated_at->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-tag-fill"></i> Kategori</span>
                            <span class="meta-val primary">{{ $recipe->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-speedometer2"></i> Kesulitan</span>
                            <span class="meta-val">{{ ucfirst($recipe->difficulty) }}</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-clock"></i> Prep</span>
                            <span class="meta-val">{{ $recipe->prep_time }} menit</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-fire"></i> Masak</span>
                            <span class="meta-val">{{ $recipe->cook_time }} menit</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-people-fill"></i> Porsi</span>
                            <span class="meta-val">{{ $recipe->servings }} orang</span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-chat-dots"></i> Komentar</span>
                            <span class="meta-val {{ $recipe->enable_comments === '1' ? 'green' : 'amber' }}">
                                {{ $recipe->enable_comments === '1' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="meta-row">
                            <span class="meta-key"><i class="bi bi-star"></i> Rating</span>
                            <span class="meta-val {{ $recipe->enable_ratings === '1' ? 'green' : 'amber' }}">
                                {{ $recipe->enable_ratings === '1' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Rating Breakdown -->
                <div class="aside-card">
                    <div class="aside-card-header">
                        <div class="aside-icon"><i class="bi bi-star-fill"></i></div>
                        <div class="aside-title">Rating Breakdown</div>
                    </div>
                    <div class="aside-card-body">
                        <div class="rating-summary">
                            <div class="rating-big">{{ number_format($recipe->rating, 1) }}</div>
                            <div class="rating-stars-big">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($recipe->rating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i == ceil($recipe->rating) && $recipe->rating - floor($recipe->rating) >= 0.5)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star empty" style="color:var(--border)"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="rating-count">dari {{ $recipe->rating > 0 ? '1' : '0' }} penilaian</div>
                        </div>
                        <div class="rating-bars">
                            <div class="rbar-row">
                                <span class="rbar-label">5</span>
                                <div class="rbar-track"><div class="rbar-fill" style="width: {{ $recipe->rating == 5 ? 100 : 0 }}%"></div></div>
                                <span class="rbar-count">{{ $recipe->rating == 5 ? 1 : 0 }}</span>
                            </div>
                            <div class="rbar-row">
                                <span class="rbar-label">4</span>
                                <div class="rbar-track"><div class="rbar-fill" style="width: {{ $recipe->rating >= 4 && $recipe->rating < 5 ? 100 : 0 }}%"></div></div>
                                <span class="rbar-count">{{ $recipe->rating >= 4 && $recipe->rating < 5 ? 1 : 0 }}</span>
                            </div>
                            <div class="rbar-row">
                                <span class="rbar-label">3</span>
                                <div class="rbar-track"><div class="rbar-fill" style="width: {{ $recipe->rating >= 3 && $recipe->rating < 4 ? 100 : 0 }}%"></div></div>
                                <span class="rbar-count">{{ $recipe->rating >= 3 && $recipe->rating < 4 ? 1 : 0 }}</span>
                            </div>
                            <div class="rbar-row">
                                <span class="rbar-label">2</span>
                                <div class="rbar-track"><div class="rbar-fill" style="width: {{ $recipe->rating >= 2 && $recipe->rating < 3 ? 100 : 0 }}%"></div></div>
                                <span class="rbar-count">{{ $recipe->rating >= 2 && $recipe->rating < 3 ? 1 : 0 }}</span>
                            </div>
                            <div class="rbar-row">
                                <span class="rbar-label">1</span>
                                <div class="rbar-track"><div class="rbar-fill" style="width: {{ $recipe->rating >= 1 && $recipe->rating < 2 ? 100 : 0 }}%"></div></div>
                                <span class="rbar-count">{{ $recipe->rating >= 1 && $recipe->rating < 2 ? 1 : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Preview -->
                <div class="aside-card">
                    <div class="aside-card-header">
                        <div class="aside-icon"><i class="bi bi-search"></i></div>
                        <div class="aside-title">SEO Preview</div>
                    </div>
                    <div class="aside-card-body">
                        <div style="border:1px solid var(--border);border-radius:10px;padding:.85rem;background:var(--bg)">
                            <div style="font-size:.68rem;color:#006621;font-family:monospace;margin-bottom:.2rem;word-break:break-all">resepkita.id › {{ $recipe->category->slug ?? 'kategori' }} › {{ $recipe->slug }}</div>
                            <div style="font-size:.95rem;font-weight:600;color:#1a0affcc;line-height:1.3;margin-bottom:.3rem">
                                {{ $recipe->meta_title ?? ($recipe->title . ' — ResepKita') }}
                            </div>
                            <div style="font-size:.78rem;color:#4d5156;line-height:1.5">
                                {{ $recipe->meta_description ?? \Illuminate\Support\Str::limit($recipe->description, 140) }}
                            </div>
                        </div>
                        <div class="mt-2 d-flex gap-2">
                            <span style="font-size:.72rem;color:var(--muted)">Title: 
                                <strong style="color:var(--success)">{{ strlen($recipe->meta_title ?? ($recipe->title . ' — ResepKita')) }}/60</strong>
                            </span>
                            <span style="font-size:.72rem;color:var(--muted)">Desc: 
                                <strong style="color:var(--success)">{{ strlen($recipe->meta_description ?? \Illuminate\Support\Str::limit($recipe->description, 140)) }}/160</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="modalHapus" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-delete">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="del-icon-wrap"><i class="bi bi-trash3"></i></div>
                    <h5 style="font-family:'Playfair Display',serif;font-weight:900;color:var(--secondary);margin-bottom:.35rem">Hapus Resep?</h5>
                    <p style="font-size:.83rem;color:var(--muted);line-height:1.6;margin-bottom:1.4rem">
                        Kamu yakin ingin menghapus resep<br>
                        <strong style="color:var(--secondary)">"{{ $recipe->title }}"</strong>?<br>
                        <span style="color:var(--danger);font-size:.77rem">⚠ Semua data termasuk langkah dan bahan akan ikut terhapus.</span>
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn-del-cancel" data-bs-dismiss="modal">Batal</button>
                        <button class="btn-del-confirm" onclick="confirmHapus(this)"><i class="bi bi-trash3"></i> Hapus</button>
                    </div>
                </div>
            </div>
    </div>

    @push('scripts')
        <script>
            // Suppress third-party script errors (e.g. TikTok, Instagram SDK internal bugs)
            window.addEventListener('error', function(e) {
                if (e.filename && (e.filename.includes('ttwstatic.com') || e.filename.includes('tiktok.com') || e.filename.includes('instagram.com'))) {
                    e.preventDefault();
                }
            }, true);
        </script>
        <script async src="https://www.tiktok.com/embed.js"></script>
        <script async src="https://www.instagram.com/embed.js"></script>
        <script>
            (function() {
                function processEmbeds() {
                    if (window.instgrm && window.instgrm.Embeds) {
                        window.instgrm.Embeds.process();
                    }
                }
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    setTimeout(processEmbeds, 100);
                } else {
                    document.addEventListener('DOMContentLoaded', processEmbeds);
                }
            })();
        </script>
    @endpush
</x-app-layout>

