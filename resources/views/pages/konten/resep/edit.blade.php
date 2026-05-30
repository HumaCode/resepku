<x-app-layout>
    @section('title', 'Edit Resep')
    @section('page-title', 'Edit Resep')

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        @vite(['resources/css/backend/resep-create.css'])
    @endpush

    @push('scripts')
        <!-- jQuery and Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- TinyMCE via CDN -->
        <script src="https://cdn.tiny.cloud/1/re1hyyagcsptel9z6bg836dptpkbrbpua7kjc4rgae0ap8kj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            window.recipeId = "{{ $recipe->id }}";
            window.masterIngredientsList = @json($masterIngredients);
        </script>
        @vite(['resources/js/backend/resep-edit.js'])
    @endpush

    <!-- Breadcrumb -->
    <x-breadcrumb-bar 
        title="Edit Resep: {{ $recipe->title }}"
        icon="bi-pencil-square"
        desc="Perbarui informasi resep Anda untuk menyempurnakan tutorial memasak."
        :items="[
            'Home' => route('dashboard'),
            'Resep' => route('recipes.index'),
            'Edit Resep' => null
        ]"
    />

    <!-- ACTION BAR (sticky bottom) -->
    <div class="action-bar" data-aos="fade-up" data-aos-delay="50">
      <button class="btn-publish" onclick="submitResep('published')">
        <i class="bi bi-send-fill"></i> Simpan &amp; Publish
      </button>
      <button class="btn-draft" onclick="submitResep('draft')">
        <i class="bi bi-floppy"></i> Simpan Draft
      </button>
      <a href="{{ route('recipes.index') }}" class="btn-cancel">
        <i class="bi bi-x-lg"></i> Batal
      </a>
      <span class="action-bar-hint"><i class="bi bi-info-circle me-1"></i>Perubahan belum tersimpan</span>
    </div>

    <!-- ══════ 2-KOLOM LAYOUT ══════ -->
    <div class="create-layout">

      <!-- ══ KOLOM KIRI — form utama ══ -->
      <div class="create-main">

        <!-- 1. INFORMASI DASAR -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="100">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-info-circle"></i></div>
            <div>
              <div class="form-card-title">Informasi Dasar</div>
              <div class="form-card-subtitle">Judul, deskripsi singkat, dan slug URL resep</div>
            </div>
          </div>
          <div class="form-card-body">

            <!-- Judul -->
            <div class="mb-3">
              <label class="form-label-custom" for="resepJudul">Judul Resep <span class="req">*</span></label>
              <input type="text" class="form-input" id="resepJudul"
                value="{{ $recipe->title }}"
                placeholder="cth: Rendang Daging Sapi Padang Asli"
                oninput="autoSlug(this.value); countChar('resepJudul','judulCount',120)"/>
              <div class="d-flex justify-content-between">
                <div class="form-hint">Judul yang jelas dan deskriptif lebih mudah ditemukan.</div>
                <div class="char-counter" id="judulCount">0 / 120</div>
              </div>
            </div>

            <!-- Slug -->
            <div class="mb-3">
              <label class="form-label-custom" for="resepSlug">Slug URL <span class="req">*</span></label>
              <div class="input-icon-wrap">
                <i class="input-icon bi bi-link-45deg"></i>
                <input type="text" class="form-input" id="resepSlug" value="{{ $recipe->slug }}" placeholder="rendang-daging-sapi-padang-asli"/>
              </div>
              <div class="form-hint">Otomatis dibuat dari judul. Bisa diedit manual.</div>
            </div>

            <!-- Deskripsi singkat -->
            <div class="mb-0">
              <label class="form-label-custom" for="resepDesc">Deskripsi Singkat <span class="req">*</span></label>
              <textarea class="form-textarea" id="resepDesc" rows="3"
                placeholder="Ceritakan sedikit tentang resep ini, asal usulnya, atau kenapa harus dicoba..."
                oninput="countChar('resepDesc','descCount',300)">{{ $recipe->description }}</textarea>
              <div class="d-flex justify-content-between">
                <div class="form-hint">Tampil sebagai ringkasan di halaman daftar resep.</div>
                <div class="char-counter" id="descCount">0 / 300</div>
              </div>
            </div>

          </div>
        </div>

        <!-- 2. KONTEN — TinyMCE -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="120">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-file-richtext"></i></div>
            <div>
              <div class="form-card-title">Konten Resep</div>
              <div class="form-card-subtitle">Deskripsi lengkap, tips, dan cerita di balik resep</div>
            </div>
          </div>
          <div class="form-card-body">
            <label class="form-label-custom">Konten Lengkap <span class="req">*</span></label>
            <div class="tinymce-wrap">
              <textarea id="resepKonten" name="resepKonten">{!! $recipe->content !!}</textarea>
            </div>
            <div class="form-hint mt-2">Gunakan editor untuk memformat teks, menambah gambar tambahan, atau catatan khusus.</div>
          </div>
        </div>

        <!-- VIDEO TUTORIAL -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="125">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-play-btn-fill"></i></div>
            <div>
              <div class="form-card-title">Video Tutorial <span style="font-size:.75rem;font-weight:400;color:var(--muted)">(Opsional)</span></div>
              <div class="form-card-subtitle">Sematkan satu atau lebih video dari YouTube, Instagram Reels, atau TikTok</div>
            </div>
          </div>
          <div class="form-card-body">
            
            <div class="video-list" id="videoList">
              @forelse($recipe->videos->sortBy('orders') as $video)
                <div class="video-row d-flex gap-2 mb-2 align-items-center">
                  <div style="flex: 1; max-width: 180px;">
                    <select class="form-select-custom video-provider">
                      <option value="">-- Pilih --</option>
                      <option value="youtube" @if($video->video_provider === 'youtube') selected @endif>📺 YouTube</option>
                      <option value="instagram" @if($video->video_provider === 'instagram') selected @endif>📸 Instagram Reels</option>
                      <option value="tiktok" @if($video->video_provider === 'tiktok') selected @endif>🎵 TikTok</option>
                    </select>
                  </div>
                  <div style="flex: 2;">
                    <div class="input-icon-wrap mb-0">
                      <i class="input-icon bi bi-link"></i>
                      <input type="url" class="form-input video-url" value="{{ $video->video_url }}" placeholder="cth: https://www.youtube.com/watch?v=... atau link Reels/TikTok"/>
                    </div>
                  </div>
                  <button class="ing-del" onclick="removeVideo(this)" type="button" title="Hapus video">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              @empty
                <!-- Row video awal kosong jika belum ada video -->
                <div class="video-row d-flex gap-2 mb-2 align-items-center">
                  <div style="flex: 1; max-width: 180px;">
                    <select class="form-select-custom video-provider">
                      <option value="">-- Pilih --</option>
                      <option value="youtube">📺 YouTube</option>
                      <option value="instagram">📸 Instagram Reels</option>
                      <option value="tiktok">🎵 TikTok</option>
                    </select>
                  </div>
                  <div style="flex: 2;">
                    <div class="input-icon-wrap mb-0">
                      <i class="input-icon bi bi-link"></i>
                      <input type="url" class="form-input video-url" placeholder="cth: https://www.youtube.com/watch?v=... atau link Reels/TikTok"/>
                    </div>
                  </div>
                  <button class="ing-del" onclick="removeVideo(this)" type="button" title="Hapus video">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              @endforelse
            </div>

            <button class="btn-add-ingredient mt-2" onclick="addVideo()" type="button">
              <i class="bi bi-plus-circle"></i> Tambah Video Tutorial
            </button>

            <div class="form-hint mt-2">Sematkan video panduan memasak agar pengguna dapat mengikuti petunjuk resep secara visual dengan lebih baik.</div>
          </div>
        </div>

        <!-- 3. BAHAN-BAHAN -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="130">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-basket2"></i></div>
            <div>
              <div class="form-card-title">Bahan-Bahan</div>
              <div class="form-card-subtitle">Daftar bahan yang diperlukan beserta takaran</div>
            </div>
          </div>
          <div class="form-card-body">

            <!-- Header kolom -->
            <div class="d-flex gap-2 mb-2" style="font-size:.72rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;padding:0 .1rem">
              <div style="flex:2">Nama Bahan</div>
              <div style="flex:1;max-width:110px">Jumlah</div>
              <div style="flex:1;max-width:110px">Satuan</div>
              <div style="width:32px"></div>
            </div>

            <div class="ingredient-list" id="ingredientList">
              @forelse($recipe->ingredients as $ingredient)
                <div class="ingredient-row">
                  <div class="ing-name">
                    <select class="form-select-custom ing-name-select" style="width:100%">
                      <option value="">-- Cari / Pilih Bahan --</option>
                      @foreach($masterIngredients as $masterIng)
                        <option value="{{ $masterIng->name }}" data-unit="{{ $masterIng->default_unit }}" @if($masterIng->name === $ingredient->name) selected @endif>{{ $masterIng->emoji }} {{ $masterIng->name }}</option>
                      @endforeach
                      @if(!$masterIngredients->contains('name', $ingredient->name))
                        <option value="{{ $ingredient->name }}" selected>{{ $ingredient->name }}</option>
                      @endif
                    </select>
                  </div>
                  <div class="ing-qty">
                    <input type="number" class="form-input" placeholder="1" value="{{ $ingredient->amount }}" min="0" step="any"/>
                  </div>
                  <div class="ing-unit">
                    <select class="form-select-custom ing-unit-select" style="width:100%">
                      <option value="{{ $ingredient->unit }}" selected>{{ $ingredient->unit }}</option>
                      @php
                        $standardUnits = ['gram', 'kg', 'ml', 'liter', 'sdm', 'sdt', 'buah', 'siung', 'butir', 'lembar', 'bungkus', 'secukupnya'];
                      @endphp
                      @foreach($standardUnits as $unit)
                        @if($unit !== $ingredient->unit)
                          <option value="{{ $unit }}">{{ $unit }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <button class="ing-del" onclick="removeIngredient(this)" title="Hapus bahan">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              @empty
                <div class="ingredient-row">
                  <div class="ing-name">
                    <select class="form-select-custom ing-name-select" style="width:100%">
                      <option value="">-- Cari / Pilih Bahan --</option>
                      @foreach($masterIngredients as $masterIng)
                        <option value="{{ $masterIng->name }}" data-unit="{{ $masterIng->default_unit }}">{{ $masterIng->emoji }} {{ $masterIng->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="ing-qty">
                    <input type="number" class="form-input" placeholder="1" min="0" step="any"/>
                  </div>
                  <div class="ing-unit">
                    <select class="form-select-custom ing-unit-select" style="width:100%">
                      <option value="gram" selected>gram</option>
                      <option value="kg">kg</option>
                      <option value="ml">ml</option>
                      <option value="liter">liter</option>
                      <option value="sdm">sdm</option>
                      <option value="sdt">sdt</option>
                      <option value="buah">buah</option>
                      <option value="siung">siung</option>
                      <option value="butir">butir</option>
                      <option value="lembar">lembar</option>
                      <option value="bungkus">bungkus</option>
                      <option value="secukupnya">secukupnya</option>
                    </select>
                  </div>
                  <button class="ing-del" onclick="removeIngredient(this)" title="Hapus bahan">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              @endforelse
            </div>

            <button class="btn-add-ingredient mt-3" onclick="addIngredient()">
              <i class="bi bi-plus-circle"></i> Tambah Bahan
            </button>

          </div>
        </div>

        <!-- 4. LANGKAH MEMASAK -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="140">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-list-ol"></i></div>
            <div>
              <div class="form-card-title">Langkah Memasak</div>
              <div class="form-card-subtitle">Urutan cara pembuatan yang jelas dan mudah diikuti</div>
            </div>
          </div>
          <div class="form-card-body">

            <div class="step-list" id="stepList">
              @forelse($recipe->steps->sortBy('step_number') as $step)
                <div class="step-item">
                  <div class="step-num">{{ $step->step_number }}</div>
                  <div class="step-content">
                    <textarea placeholder="Tuliskan langkah memasak..." rows="2">{{ $step->description }}</textarea>
                    <div class="step-img-area">
                      @if($step->image)
                        {{-- Existing image: show preview, allow replacement --}}
                        <label class="step-img-label" style="display:none">
                          <input type="file" class="step-img-input" accept="image/*" onchange="previewStepImage(this)">
                          <span class="sil-icon"><i class="bi bi-camera-fill"></i></span>
                          <span class="sil-title">Upload Foto Langkah</span>
                          <span class="sil-sub">JPG, PNG, WebP · Maks 3MB · Opsional</span>
                        </label>
                        <div class="step-img-preview" style="display:block" data-existing="{{ asset('storage/' . $step->image) }}">
                          <img src="{{ asset('storage/' . $step->image) }}" alt="Foto Langkah {{ $step->step_number }}">
                          <div class="step-img-overlay">
                            <button type="button" class="step-img-replace" onclick="replaceStepImage(this)" title="Ganti foto">
                              <i class="bi bi-arrow-repeat"></i> Ganti
                            </button>
                            <button type="button" class="step-img-remove" onclick="removeStepImage(this)">
                              <i class="bi bi-trash3-fill"></i> Hapus
                            </button>
                          </div>
                        </div>
                      @else
                        {{-- No existing image: show upload drop zone --}}
                        <label class="step-img-label">
                          <input type="file" class="step-img-input" accept="image/*" onchange="previewStepImage(this)">
                          <span class="sil-icon"><i class="bi bi-camera-fill"></i></span>
                          <span class="sil-title">Upload Foto Langkah</span>
                          <span class="sil-sub">JPG, PNG, WebP · Maks 3MB · Opsional</span>
                        </label>
                        <div class="step-img-preview" style="display:none">
                          <img src="" alt="preview">
                          <div class="step-img-overlay">
                            <button type="button" class="step-img-replace" onclick="replaceStepImage(this)" title="Ganti foto">
                              <i class="bi bi-arrow-repeat"></i> Ganti
                            </button>
                            <button type="button" class="step-img-remove" onclick="removeStepImage(this)">
                              <i class="bi bi-trash3-fill"></i> Hapus
                            </button>
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                  <div class="step-actions">
                    <button class="step-btn step-drag" title="Drag untuk urutkan"><i class="bi bi-grip-vertical"></i></button>
                    <button class="step-btn del" onclick="removeStep(this)" title="Hapus langkah"><i class="bi bi-trash"></i></button>
                  </div>
                </div>
              @empty
                <div class="step-item">
                  <div class="step-num">1</div>
                  <div class="step-content">
                    <textarea placeholder="Tuliskan langkah pertama..." rows="2"></textarea>
                    <div class="step-img-area">
                      <label class="step-img-label">
                        <input type="file" class="step-img-input" accept="image/*" onchange="previewStepImage(this)">
                        <span class="sil-icon"><i class="bi bi-camera-fill"></i></span>
                        <span class="sil-title">Upload Foto Langkah</span>
                        <span class="sil-sub">JPG, PNG, WebP · Maks 3MB · Opsional</span>
                      </label>
                      <div class="step-img-preview" style="display:none">
                        <img src="" alt="preview">
                        <div class="step-img-overlay">
                          <button type="button" class="step-img-replace" onclick="replaceStepImage(this)">
                            <i class="bi bi-arrow-repeat"></i> Ganti
                          </button>
                          <button type="button" class="step-img-remove" onclick="removeStepImage(this)">
                            <i class="bi bi-trash3-fill"></i> Hapus
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="step-actions">
                    <button class="step-btn step-drag" title="Drag untuk urutkan"><i class="bi bi-grip-vertical"></i></button>
                    <button class="step-btn del" onclick="removeStep(this)" title="Hapus langkah"><i class="bi bi-trash"></i></button>
                  </div>
                </div>
              @endforelse
            </div>

            <button class="btn-add-step mt-3" onclick="addStep()">
              <i class="bi bi-plus-circle"></i> Tambah Langkah
            </button>

          </div>
        </div>

        <!-- 5. INFORMASI NUTRISI -->
        <div class="form-card" data-aos="fade-up" data-aos-delay="150">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-heart-pulse"></i></div>
            <div>
              <div class="form-card-title">Informasi Nutrisi <span style="font-size:.75rem;font-weight:400;color:var(--muted)">(Opsional)</span></div>
              <div class="form-card-subtitle">Per satu porsi sajian</div>
            </div>
          </div>
          <div class="form-card-body">
            <div class="nutrition-grid">
              <div class="nutrition-item">
                <label>Kalori (kkal)</label>
                <input type="number" class="form-input" id="nutrisiKalori" value="{{ $recipe->calories }}" placeholder="—" min="0"/>
              </div>
              <div class="nutrition-item">
                <label>Protein (g)</label>
                <input type="number" class="form-input" id="nutrisiProtein" value="{{ $recipe->protein }}" placeholder="—" min="0"/>
              </div>
              <div class="nutrition-item">
                <label>Lemak (g)</label>
                <input type="number" class="form-input" id="nutrisiLemak" value="{{ $recipe->fat }}" placeholder="—" min="0"/>
              </div>
              <div class="nutrition-item">
                <label>Karbohidrat (g)</label>
                <input type="number" class="form-input" id="nutrisiKarbo" value="{{ $recipe->carbs }}" placeholder="—" min="0"/>
              </div>
              <div class="nutrition-item">
                <label>Serat (g)</label>
                <input type="number" class="form-input" id="nutrisiSerat" value="{{ $recipe->fiber }}" placeholder="—" min="0"/>
              </div>
              <div class="nutrition-item">
                <label>Gula (g)</label>
                <input type="number" class="form-input" id="nutrisiGula" value="{{ $recipe->sugar }}" placeholder="—" min="0"/>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /create-main -->

      <!-- ══ KOLOM KANAN — aside / sidebar form ══ -->
      <div class="create-aside" data-aos="fade-left" data-aos-delay="120">

        <!-- FOTO UTAMA -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-image"></i></div>
            <div>
              <div class="form-card-title">Foto Utama</div>
              <div class="form-card-subtitle">Gambar cover resep</div>
            </div>
          </div>
          <div class="form-card-body">
            <!-- Upload area -->
            <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotoInput').click()" @if($recipe->getFirstMediaUrl('cover')) style="display:none" @endif>
              <input type="file" id="fotoInput" accept="image/*" onchange="previewFoto(event)" style="display:none"/>
              <div class="upload-icon"><i class="bi bi-camera"></i></div>
              <div class="upload-title">Upload Foto Resep</div>
              <div class="upload-sub">Drag &amp; drop atau <strong>klik untuk pilih</strong><br>JPG, PNG, WebP — maks. 5 MB<br>Rekomendasi: 1200 × 800 px</div>
            </div>
            <!-- Preview setelah upload -->
            <div class="upload-preview mt-2 @if($recipe->getFirstMediaUrl('cover')) show @endif" id="uploadPreview">
              <img id="previewImg" src="{{ $recipe->getFirstMediaUrl('cover') ?? '' }}" alt="Preview"/>
              <div class="upload-preview-overlay">
                <button class="preview-btn change" onclick="document.getElementById('fotoInput').click()"><i class="bi bi-arrow-repeat me-1"></i>Ganti</button>
                <button class="preview-btn remove" onclick="removeFoto()"><i class="bi bi-trash me-1"></i>Hapus</button>
              </div>
            </div>
          </div>
        </div>

        <!-- PENGATURAN RESEP -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-sliders"></i></div>
            <div>
              <div class="form-card-title">Pengaturan</div>
              <div class="form-card-subtitle">Klasifikasi dan metadata resep</div>
            </div>
          </div>
          <div class="form-card-body">

            <!-- Kategori — Select2 single -->
            <div class="mb-3">
              <label class="form-label-custom" for="selKategori">Kategori <span class="req">*</span></label>
              <select id="selKategori" class="select2-target">
                <option value=""></option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" @if($category->id == $recipe->category_id) selected @endif>{{ $category->icon }} {{ $category->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Tags — Select2 multiple -->
            <div class="mb-3">
              <label class="form-label-custom" for="selTags">Tags <span class="opt">(opsional)</span></label>
              <select id="selTags" class="select2-target-multi" multiple>
                @foreach($tags as $tag)
                  <option value="{{ $tag->id }}" @if($recipe->tags->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
                @endforeach
              </select>
              <div class="form-hint">Pilih satu atau lebih tag yang relevan.</div>
            </div>

            <!-- Tingkat kesulitan -->
            <div class="mb-3">
              <label class="form-label-custom" for="selKesulitan">Tingkat Kesulitan <span class="req">*</span></label>
              <select id="selKesulitan" class="form-select-custom">
                <option value="">-- Pilih --</option>
                <option value="mudah" @if($recipe->difficulty === 'mudah') selected @endif>😊 Mudah</option>
                <option value="sedang" @if($recipe->difficulty === 'sedang') selected @endif>🤔 Sedang</option>
                <option value="sulit" @if($recipe->difficulty === 'sulit') selected @endif>😅 Sulit</option>
                <option value="expert" @if($recipe->difficulty === 'expert') selected @endif>👨‍🍳 Expert</option>
              </select>
            </div>

            <div class="section-divider"></div>

            <!-- Waktu & Porsi -->
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label-custom">Waktu Prep <span class="req">*</span></label>
                <div class="input-icon-wrap">
                  <i class="input-icon bi bi-clock"></i>
                  <input type="number" class="form-input" id="waktuPrep" value="{{ $recipe->prep_time }}" placeholder="15" min="0"/>
                </div>
                <div class="form-hint">menit</div>
              </div>
              <div class="col-6">
                <label class="form-label-custom">Waktu Masak <span class="req">*</span></label>
                <div class="input-icon-wrap">
                  <i class="input-icon bi bi-fire"></i>
                  <input type="number" class="form-input" id="waktuMasak" value="{{ $recipe->cook_time }}" placeholder="60" min="0"/>
                </div>
                <div class="form-hint">menit</div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label-custom">Jumlah Porsi <span class="req">*</span></label>
              <div class="input-icon-wrap">
                <i class="input-icon bi bi-people"></i>
                <input type="number" class="form-input" id="porsi" value="{{ $recipe->servings }}" placeholder="4" min="1"/>
              </div>
              <div class="form-hint">orang / sajian</div>
            </div>

            <div class="section-divider"></div>

            <!-- Toggle: Unggulan -->
            <div class="toggle-wrap mb-2">
              <div>
                <div class="toggle-label">⭐ Tandai Unggulan</div>
                <div class="toggle-sub">Tampil di halaman utama</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleUnggulan" @if($recipe->is_featured == '1') checked @endif/>
              </div>
            </div>

            <!-- Toggle: Komentar -->
            <div class="toggle-wrap mb-2">
              <div>
                <div class="toggle-label"><i class="bi bi-chat-dots me-1"></i>Aktifkan Komentar</div>
                <div class="toggle-sub">Biarkan pengguna berkomentar</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleKomentar" @if($recipe->enable_comments == '1') checked @endif/>
              </div>
            </div>

            <!-- Toggle: Rating -->
            <div class="toggle-wrap">
              <div>
                <div class="toggle-label"><i class="bi bi-star me-1"></i>Aktifkan Rating</div>
                <div class="toggle-sub">Biarkan pengguna memberi rating</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="toggleRating" @if($recipe->enable_ratings == '1') checked @endif/>
              </div>
            </div>

          </div>
        </div>

        <!-- SEO META -->
        <div class="form-card">
          <div class="form-card-header">
            <div class="form-card-icon"><i class="bi bi-search"></i></div>
            <div>
              <div class="form-card-title">SEO Meta</div>
              <div class="form-card-subtitle">Optimasi mesin pencari</div>
            </div>
          </div>
          <div class="form-card-body">
            <div class="mb-3">
              <label class="form-label-custom" for="metaTitle">Meta Title <span class="opt">(opsional)</span></label>
              <input type="text" class="form-input" id="metaTitle"
                value="{{ $recipe->meta_title }}"
                placeholder="cth: Rendang Sapi Padang Asli — ResepKita"
                oninput="countChar('metaTitle','metaTitleCount',60)"/>
              <div class="d-flex justify-content-between">
                <div class="form-hint">Kosongkan untuk pakai judul resep.</div>
                <div class="char-counter" id="metaTitleCount">0 / 60</div>
              </div>
            </div>
            <div class="mb-0">
              <label class="form-label-custom" for="metaDesc">Meta Description <span class="opt">(opsional)</span></label>
              <textarea class="form-textarea" id="metaDesc" rows="3"
                placeholder="Deskripsi singkat untuk mesin pencari..."
                oninput="countChar('metaDesc','metaDescCount',160)">{{ $recipe->meta_description }}</textarea>
              <div class="d-flex justify-content-between">
                <div class="form-hint">Maks. 160 karakter.</div>
                <div class="char-counter" id="metaDescCount">0 / 160</div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /create-aside -->

    </div><!-- /create-layout -->
</x-app-layout>
