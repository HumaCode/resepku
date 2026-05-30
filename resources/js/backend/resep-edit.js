document.addEventListener('DOMContentLoaded', () => {
  /* ── TinyMCE Initialization ── */
  if (typeof tinymce !== 'undefined') {
    tinymce.init({
      selector: '#resepKonten',
      license_key: 'gpl',
      height: 420,
      menubar: false,
      branding: false,
      resize: true,
      skin: 'oxide',
      content_css: 'default',
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'wordcount'
      ],
      toolbar:
        'undo redo | blocks | ' +
        'bold italic underline strikethrough | forecolor backcolor | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist | blockquote | link image | ' +
        'removeformat | code fullscreen',
      toolbar_mode: 'wrap',
      block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Preformatted=pre',
      content_style: `
        body {
          font-family: 'DM Sans', -apple-system, sans-serif;
          font-size: 15px;
          color: #1a0e05;
          line-height: 1.75;
          padding: 1rem 1.25rem;
          background: #ffffff;
        }
        h2, h3, h4 {
          font-family: 'Playfair Display', serif;
          font-weight: 900;
          color: #2d1b0e;
          margin-top: 1.5em;
          margin-bottom: .5em;
        }
        p { margin-bottom: 1em; }
        a { color: #e85d26; }
        blockquote {
          border-left: 4px solid #e85d26;
          margin: 1em 0;
          padding: .5em 1em;
          background: rgba(232,93,38,.06);
          border-radius: 0 8px 8px 0;
          color: #8a7060;
          font-style: italic;
        }
        img { max-width: 100%; border-radius: 8px; }
        ul, ol { padding-left: 1.5em; }
        li { margin-bottom: .35em; }
      `,
      content_css_cors: true,
      setup: function(editor) {
        editor.on('input change', function() {
          editor.save();
        });
        editor.on('focus', function() {
          const wrap = document.querySelector('.tinymce-wrap');
          if (wrap) {
            wrap.style.borderColor = 'var(--primary)';
            wrap.style.boxShadow   = '0 0 0 3px rgba(232,93,38,.1)';
          }
        });
        editor.on('blur', function() {
          const wrap = document.querySelector('.tinymce-wrap');
          if (wrap) {
            wrap.style.borderColor = '';
            wrap.style.boxShadow   = '';
          }
        });
      }
    });
  }

  /* ── Select2 Initialization ── */
  if (window.jQuery && window.jQuery.fn.select2) {
    $('#selKategori').select2({
      placeholder: '-- Pilih Kategori --',
      allowClear: true,
      width: '100%'
    });

    $('#selTags').select2({
      placeholder: 'Cari atau pilih tag...',
      allowClear: true,
      width: '100%',
      multiple: true,
      closeOnSelect: false
    }).on('change select2:select select2:unselect select2:open select2:close', function() {
      const $container = $(this).next('.select2-container');
      setTimeout(() => {
        const $input = $container.find('.select2-search__field');
        if ($input.length && !$input.val()) {
          $input.attr('placeholder', 'Cari atau pilih tag...');
        }
      }, 50);
    });

    // Initialize ingredients select2 function
    window.initIngredientSelect2 = function(element) {
      $(element).select2({
        placeholder: 'Cari atau pilih bahan...',
        allowClear: true,
        tags: true, // Allow custom ingredients
        width: '100%'
      }).on('change select2:select', function() {
        const selectedOpt = $(this).find('option:selected');
        const defaultUnitStr = selectedOpt.data('unit'); // e.g. "buah, gram"
        const row = $(this).closest('.ingredient-row');
        const unitSelect = row.find('.ing-unit-select');

        // Autofill amount to 1 if empty
        const qtyInput = row.find('.ing-qty input');
        if (qtyInput.length && !qtyInput.val()) {
          qtyInput.val(1);
        }

        if (defaultUnitStr && unitSelect.length) {
          const units = defaultUnitStr.split(',').map(u => u.trim()).filter(u => u.length > 0);
          if (units.length > 0) {
            unitSelect.empty();
            
            // Add ingredient's units first
            units.forEach(unit => {
              unitSelect.append(new Option(unit, unit, false, false));
            });
            
            // Add standard fallback units
            const commonUnits = ['gram', 'kg', 'ml', 'liter', 'sdm', 'sdt', 'buah', 'siung', 'butir', 'lembar', 'bungkus', 'secukupnya'];
            commonUnits.forEach(unit => {
              if (!units.includes(unit)) {
                unitSelect.append(new Option(unit, unit, false, false));
              }
            });
            
            // Auto-select the first unit from the list
            unitSelect.val(units[0]).trigger('change');
          }
        }
      });
    };

    // Initialize unit select2 function
    window.initIngredientUnitSelect2 = function(element) {
      $(element).select2({
        placeholder: 'satuan',
        tags: true, // Allow typing custom unit
        width: '100%'
      });
    };

    // Run for initial rows
    $('.ing-name-select').each(function() {
      window.initIngredientSelect2(this);
    });
    $('.ing-unit-select').each(function() {
      window.initIngredientUnitSelect2(this);
    });

    setTimeout(() => {
      const $input = $('#selTags').next('.select2-container').find('.select2-search__field');
      if ($input.length && !$input.val()) {
        $input.attr('placeholder', 'Cari atau pilih tag...');
      }
    }, 150);
  }

  /* ── Video URL Auto-Extractor & Provider Auto-Detector ── */
  function extractUrlFromEmbed(html) {
    html = html.trim();
    if (!html.startsWith('<') && !html.includes('<blockquote') && !html.includes('<iframe') && !html.includes('<script')) {
      return null;
    }
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    
    const tiktok = doc.querySelector('blockquote.tiktok-embed');
    if (tiktok && tiktok.getAttribute('cite')) return tiktok.getAttribute('cite');

    const insta = doc.querySelector('blockquote.instagram-media');
    if (insta && insta.getAttribute('data-instgrm-permalink')) return insta.getAttribute('data-instgrm-permalink');

    const iframe = doc.querySelector('iframe');
    if (iframe && iframe.getAttribute('src')) {
      const src = iframe.getAttribute('src');
      return src.startsWith('//') ? 'https:' + src : src;
    }

    let match = html.match(/cite=["'](https?:\/\/[^"']+)["']/i);
    if (match) return match[1];

    match = html.match(/data-instgrm-permalink=["'](https?:\/\/[^"']+)["']/i);
    if (match) return match[1];

    match = html.match(/href=["'](https?:\/\/[^"']+)["']/i);
    if (match) return match[1];

    match = html.match(/src=["']([^"']+)["']/i);
    if (match) {
      let src = match[1];
      return src.startsWith('//') ? 'https:' + src : src;
    }
    return null;
  }

  function detectProvider(url) {
    const lower = url.toLowerCase();
    if (lower.includes('tiktok.com')) return 'tiktok';
    if (lower.includes('instagram.com')) return 'instagram';
    if (lower.includes('youtube.com') || lower.includes('youtu.be')) return 'youtube';
    return null;
  }

  const videoList = document.getElementById('videoList');
  if (videoList) {
    videoList.addEventListener('input', e => {
      if (e.target && e.target.classList.contains('video-url')) {
        const val = e.target.value.trim();
        if (val.startsWith('<') || val.includes('<blockquote') || val.includes('<iframe') || val.includes('<script')) {
          const extracted = extractUrlFromEmbed(val);
          if (extracted) {
            e.target.value = extracted;
            const row = e.target.closest('.video-row');
            const provSelect = row ? row.querySelector('.video-provider') : null;
            if (provSelect) {
              const detected = detectProvider(extracted);
              if (detected) provSelect.value = detected;
            }
          }
        } else if (val) {
          const row = e.target.closest('.video-row');
          const provSelect = row ? row.querySelector('.video-provider') : null;
          if (provSelect && !provSelect.value) {
            const detected = detectProvider(val);
            if (detected) provSelect.value = detected;
          }
        }
      }
    });
  }

  /* ── Upload Area Drag & Drop ── */
  const ua = document.getElementById('uploadArea');
  if (ua) {
    ua.addEventListener('dragover',  e => { e.preventDefault(); ua.classList.add('drag-over'); });
    ua.addEventListener('dragleave', () => ua.classList.remove('drag-over'));
    ua.addEventListener('drop', e => {
      e.preventDefault(); ua.classList.remove('drag-over');
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file);
        const fi = document.getElementById('fotoInput'); 
        if (fi) {
          fi.files = dt.files;
          previewFoto({ target: fi });
        }
      }
    });
  }
  
  // Renumber steps initially
  renumberSteps();
});

/* ── Utilitas Form ── */

/* Auto generate slug dari judul */
window.autoSlug = function(val) {
  const slug = val
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
  const el = document.getElementById('resepSlug');
  if (el) el.value = slug;
}

/* Hitung karakter & warnai counter */
window.countChar = function(inputId, counterId, max) {
  const inputEl = document.getElementById(inputId);
  if (!inputEl) return;
  const val = inputEl.value.length;
  const el  = document.getElementById(counterId);
  if (!el) return;
  el.textContent = val + ' / ' + max;
  el.classList.remove('warn', 'over');
  if (val > max)              el.classList.add('over');
  else if (val > max * .85)   el.classList.add('warn');
}

/* Upload foto preview */
window.previewFoto = function(event) {
  const file = event.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const previewImg = document.getElementById('previewImg');
    const uploadArea = document.getElementById('uploadArea');
    const uploadPreview = document.getElementById('uploadPreview');
    if (previewImg) previewImg.src = e.target.result;
    if (uploadArea) uploadArea.style.display = 'none';
    if (uploadPreview) uploadPreview.classList.add('show');
  };
  reader.readAsDataURL(file);
}

window.removeFoto = function() {
  const fi = document.getElementById('fotoInput');
  const previewImg = document.getElementById('previewImg');
  const uploadPreview = document.getElementById('uploadPreview');
  const uploadArea = document.getElementById('uploadArea');
  if (fi) fi.value = '';
  if (previewImg) previewImg.src = '';
  if (uploadPreview) uploadPreview.classList.remove('show');
  if (uploadArea) uploadArea.style.display = '';
}

/* ── Ingredient rows ── */
window.addIngredient = function() {
  const list = document.getElementById('ingredientList');
  if (!list) return;

  let optionsHtml = '<option value="">-- Cari / Pilih Bahan --</option>';
  if (window.masterIngredientsList && Array.isArray(window.masterIngredientsList)) {
    window.masterIngredientsList.forEach(ing => {
      const emoji = ing.emoji ? ing.emoji + ' ' : '';
      optionsHtml += `<option value="${ing.name}" data-unit="${ing.default_unit || ''}">${emoji}${ing.name}</option>`;
    });
  }

  const row = document.createElement('div');
  row.className = 'ingredient-row';
  row.innerHTML = `
    <div class="ing-name">
      <select class="form-select-custom ing-name-select" style="width:100%">
        ${optionsHtml}
      </select>
    </div>
    <div class="ing-qty"><input type="number" class="form-input" placeholder="1" min="0" step="any"/></div>
    <div class="ing-unit">
      <select class="form-select-custom ing-unit-select" style="width:100%">
        <option value="gram">gram</option>
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
    <button class="ing-del" onclick="removeIngredient(this)" title="Hapus"><i class="bi bi-x-lg"></i></button>
  `;
  list.appendChild(row);

  const selectEl = row.querySelector('.ing-name-select');
  if (window.initIngredientSelect2) {
    window.initIngredientSelect2(selectEl);
  }

  const unitEl = row.querySelector('.ing-unit-select');
  if (window.initIngredientUnitSelect2) {
    window.initIngredientUnitSelect2(unitEl);
  }

  // Open Select2 immediately upon add
  setTimeout(() => {
    $(selectEl).select2('open');
  }, 50);

  row.style.opacity = '0'; row.style.transform = 'translateY(-8px)';
  requestAnimationFrame(() => {
    row.style.transition = 'opacity .25s, transform .25s';
    row.style.opacity = '1'; row.style.transform = 'translateY(0)';
  });
}

window.removeIngredient = function(btn) {
  const row = btn.closest('.ingredient-row');
  const list = document.getElementById('ingredientList');
  if (!list || !row) return;
  if (list.querySelectorAll('.ingredient-row').length <= 1) return;
  row.style.transition = 'opacity .2s, transform .2s';
  row.style.opacity = '0'; row.style.transform = 'translateX(12px)';
  setTimeout(() => row.remove(), 200);
}

/* ── Video rows ── */
window.addVideo = function() {
  const list = document.getElementById('videoList');
  if (!list) return;
  const row = document.createElement('div');
  row.className = 'video-row d-flex gap-2 mb-2 align-items-center';
  row.innerHTML = `
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
  `;
  list.appendChild(row);
  row.querySelector('select').focus();
  row.style.opacity = '0'; row.style.transform = 'translateY(-8px)';
  requestAnimationFrame(() => {
    row.style.transition = 'opacity .25s, transform .25s';
    row.style.opacity = '1'; row.style.transform = 'translateY(0)';
  });
}

window.removeVideo = function(btn) {
  const row = btn.closest('.video-row');
  const list = document.getElementById('videoList');
  if (!list || !row) return;
  if (list.querySelectorAll('.video-row').length <= 1) {
    row.querySelector('select').value = '';
    row.querySelector('input').value = '';
    return;
  }
  row.style.transition = 'opacity .2s, transform .2s';
  row.style.opacity = '0'; row.style.transform = 'translateX(12px)';
  setTimeout(() => row.remove(), 200);
}

/* ── Step rows ── */
let stepCount = 0;
window.addStep = function() {
  const list = document.getElementById('stepList');
  if (!list) return;
  stepCount = list.querySelectorAll('.step-item').length + 1;
  const item = document.createElement('div');
  item.className = 'step-item';
  item.innerHTML = `
    <div class="step-num">${stepCount}</div>
    <div class="step-content">
      <textarea placeholder="Tuliskan langkah ke-${stepCount}..." rows="2"></textarea>
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
  `;
  list.appendChild(item);
  item.querySelector('textarea').focus();
  item.style.opacity = '0'; item.style.transform = 'translateY(-8px)';
  requestAnimationFrame(() => {
    item.style.transition = 'opacity .25s, transform .25s';
    item.style.opacity = '1'; item.style.transform = 'translateY(0)';
  });
}

window.removeStep = function(btn) {
  const item = btn.closest('.step-item');
  const list = document.getElementById('stepList');
  if (!list || !item) return;
  if (list.querySelectorAll('.step-item').length <= 1) return;
  item.style.transition = 'opacity .2s, transform .2s';
  item.style.opacity = '0'; item.style.transform = 'translateX(12px)';
  setTimeout(() => {
    item.remove();
    renumberSteps();
  }, 200);
}

function renumberSteps() {
  document.querySelectorAll('#stepList .step-num').forEach((num, i) => {
    num.textContent = i + 1;
  });
  const list = document.getElementById('stepList');
  if (list) {
    stepCount = list.querySelectorAll('.step-item').length;
  }
}

/* ── Submit / Save ── */
window.submitResep = function(status) {
  if (typeof tinymce !== 'undefined') tinymce.triggerSave();

  const judulEl = document.getElementById('resepJudul');
  if (!judulEl) return;
  const judul = judulEl.value.trim();
  if (!judul) {
    judulEl.classList.add('is-error');
    judulEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    judulEl.focus();
    return;
  }
  judulEl.classList.remove('is-error');

  const btn = status === 'published'
    ? document.querySelector('.btn-publish')
    : document.querySelector('.btn-draft');
  if (!btn) return;
  const origHTML = btn.innerHTML;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
  btn.disabled  = true;

  // 1. Gather basic form data
  const formData = new FormData();
  formData.append('_method', 'PUT'); // Method spoofing for Laravel PUT file uploads
  formData.append('title', judul);
  formData.append('slug', document.getElementById('resepSlug').value.trim());
  formData.append('description', document.getElementById('resepDesc').value.trim());
  
  const kontenVal = (typeof tinymce !== 'undefined') ? tinymce.get('resepKonten').getContent() : '';
  formData.append('content', kontenVal);
  
  formData.append('category_id', document.getElementById('selKategori').value);
  formData.append('difficulty', document.getElementById('selKesulitan').value);
  formData.append('prep_time', document.getElementById('waktuPrep').value);
  formData.append('cook_time', document.getElementById('waktuMasak').value);
  formData.append('servings', document.getElementById('porsi').value);
  
  // Nutrition
  formData.append('calories', document.getElementById('nutrisiKalori').value);
  formData.append('protein', document.getElementById('nutrisiProtein').value);
  formData.append('fat', document.getElementById('nutrisiLemak').value);
  formData.append('carbs', document.getElementById('nutrisiKarbo').value);
  formData.append('fiber', document.getElementById('nutrisiSerat').value);
  formData.append('sugar', document.getElementById('nutrisiGula').value);
  
  // Switches
  formData.append('is_featured', document.getElementById('toggleUnggulan').checked ? '1' : '0');
  formData.append('enable_comments', document.getElementById('toggleKomentar').checked ? '1' : '0');
  formData.append('enable_ratings', document.getElementById('toggleRating').checked ? '1' : '0');
  
  // SEO Meta
  formData.append('meta_title', document.getElementById('metaTitle').value.trim());
  formData.append('meta_description', document.getElementById('metaDesc').value.trim());
  
  // Status
  formData.append('status', status);

  // Cover image
  const coverFile = document.getElementById('fotoInput').files[0];
  if (coverFile) {
    formData.append('cover', coverFile);
  }

  // Tags (Select2)
  const selectedTags = jQuery('#selTags').val() || [];
  selectedTags.forEach((tagId, index) => {
    formData.append(`tags[${index}]`, tagId);
  });

  // Ingredients
  document.querySelectorAll('#ingredientList .ingredient-row').forEach((row, index) => {
    const nameEl = row.querySelector('.ing-name select') || row.querySelector('.ing-name input');
    const name = nameEl ? nameEl.value.trim() : '';
    const amountEl = row.querySelector('.ing-qty input');
    let amount = amountEl ? amountEl.value.trim() : '';
    if (!amount && amountEl && amountEl.placeholder) {
      amount = amountEl.placeholder.trim();
    }
    const unitEl = row.querySelector('.ing-unit select') || row.querySelector('.ing-unit input');
    const unit = unitEl ? unitEl.value.trim() : '';
    
    console.log(`Submitting ingredient row ${index}: name="${name}", amount="${amount}", unit="${unit}"`);

    if (name) {
      formData.append(`ingredients[${index}][name]`, name);
      formData.append(`ingredients[${index}][amount]`, amount);
      formData.append(`ingredients[${index}][unit]`, unit);
    }
  });

  // Steps
  document.querySelectorAll('#stepList .step-item').forEach((item, index) => {
    const num = item.querySelector('.step-num').textContent.trim();
    const desc = item.querySelector('.step-content textarea').value.trim();
    if (desc) {
      formData.append(`steps[${index}][step_number]`, num);
      formData.append(`steps[${index}][description]`, desc);
      // Attach new step image if uploaded
      const imgInput = item.querySelector('.step-img-input');
      if (imgInput && imgInput.files && imgInput.files[0]) {
        formData.append(`steps[${index}][image_file]`, imgInput.files[0]);
      }
    }
  });

  // Videos
  document.querySelectorAll('#videoList .video-row').forEach((row, index) => {
    const provider = row.querySelector('.video-provider').value;
    const url = row.querySelector('.video-url').value.trim();
    if (provider && url) {
      formData.append(`videos[${index}][video_provider]`, provider);
      formData.append(`videos[${index}][video_url]`, url);
    }
  });

  // AJAX Fetch request
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const recipeId = window.recipeId;

  fetch(`/recipes/${recipeId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(response => {
    return response.json().then(data => ({
      status: response.status,
      ok: response.ok,
      body: data
    }));
  })
  .then(res => {
    btn.innerHTML = origHTML;
    btn.disabled  = false;

    if (res.ok && res.body.success) {
      if (window.PA) {
        window.PA.toast({
          type: 'success',
          title: 'Berhasil',
          message: res.body.message,
          duration: 3000
        });
      }
      setTimeout(() => {
        window.location.href = res.body.redirect;
      }, 1000);
    } else {
      // Handle validation errors
      const errors = res.body.errors;
      if (errors) {
        let errorMsg = '';
        Object.keys(errors).forEach(key => {
          errorMsg += `${errors[key][0]}<br>`;
        });
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Kesalahan Validasi',
            message: errorMsg,
            duration: 5000
          });
        }
      } else {
        if (window.PA) {
          window.PA.toast({
            type: 'error',
            title: 'Gagal',
            message: res.body.message || 'Terjadi kesalahan saat menyimpan resep.',
            duration: 3000
          });
        }
      }
    }
  })
  .catch(err => {
    btn.innerHTML = origHTML;
    btn.disabled  = false;
    console.error(err);
    if (window.PA) {
      window.PA.toast({
        type: 'error',
        title: 'Error',
        message: 'Gagal menghubungi server.',
        duration: 3000
      });
    }
  });
}

/* ── Step Image Upload Helpers ── */
window.previewStepImage = function(input) {
  if (!input.files || !input.files[0]) return;
  const area    = input.closest('.step-img-area');
  if (!area) return;
  const label   = area.querySelector('.step-img-label');
  const preview = area.querySelector('.step-img-preview');
  const img     = preview ? preview.querySelector('img') : null;
  const reader  = new FileReader();
  reader.onload = function(e) {
    if (img) img.src = e.target.result;
    if (label)   label.style.display   = 'none';
    if (preview) preview.style.display = 'block';
  };
  reader.readAsDataURL(input.files[0]);
};

window.removeStepImage = function(btn) {
  const area    = btn.closest('.step-img-area');
  if (!area) return;
  const label   = area.querySelector('.step-img-label');
  const preview = area.querySelector('.step-img-preview');
  const img     = preview ? preview.querySelector('img') : null;
  const input   = area.querySelector('.step-img-input');
  if (input)   input.value = '';
  if (img)     img.src     = '';
  if (preview) preview.style.display = 'none';
  if (label)   label.style.display   = '';
};

window.replaceStepImage = function(btn) {
  // Trigger click on the hidden file input of this step
  const area  = btn.closest('.step-img-area');
  if (!area) return;
  const input = area.querySelector('.step-img-input');
  if (input)  input.click();
};
