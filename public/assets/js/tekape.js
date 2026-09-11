// TeKaPe.id Interactive Frontend Scripts

document.addEventListener('DOMContentLoaded', () => {
  // Password Visibility Toggle
  document.querySelectorAll('.password-toggle-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const input = btn.previousElementSibling;
      if (input && input.tagName === 'INPUT') {
        if (input.type === 'password') {
          input.type = 'text';
          btn.innerHTML = `<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>`;
        } else {
          input.type = 'password';
          btn.innerHTML = `<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
        }
      }
    });
  });

  // Modal handlers
  document.querySelectorAll('[data-modal-target]').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = trigger.getAttribute('data-modal-target');
      const modal = document.getElementById(targetId);
      if (modal) modal.classList.add('open');
    });
  });

  document.querySelectorAll('[data-modal-close]').forEach(closeBtn => {
    closeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const modal = closeBtn.closest('.modal-backdrop');
      if (modal) modal.classList.remove('open');
    });
  });

  // Image Upload Preview in Question Editor
  const imageInput = document.getElementById('question-image-input');
  if (imageInput) {
    imageInput.addEventListener('change', function(e) {
      const file = this.files[0];
      const previewBox = document.getElementById('image-preview-container');
      const previewImg = document.getElementById('image-preview-img');
      if (file && previewBox && previewImg) {
        const reader = new FileReader();
        reader.onload = function(evt) {
          previewImg.src = evt.target.result;
          previewBox.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Live Formula Calculator Preview
  const twkInput = document.getElementById('calc-twk');
  const tiuInput = document.getElementById('calc-tiu');
  const tkpInput = document.getElementById('calc-tkp');
  const resultPreview = document.getElementById('calc-final-result');

  function updateFormulaPreview() {
    if (!resultPreview) return;
    let scores = [];
    if (twkInput && !twkInput.disabled && twkInput.value !== '') scores.push(parseFloat(twkInput.value) || 0);
    if (tiuInput && !tiuInput.disabled && tiuInput.value !== '') scores.push(parseFloat(tiuInput.value) || 0);
    if (tkpInput && !tkpInput.disabled && tkpInput.value !== '') scores.push(parseFloat(tkpInput.value) || 0);

    const count = scores.length;
    if (count === 0) {
      resultPreview.innerText = '0.00';
      return;
    }
    const sum = scores.reduce((a, b) => a + b, 0);
    const finalVal = (sum / count).toFixed(2);
    resultPreview.innerText = finalVal;
  }

  [twkInput, tiuInput, tkpInput].forEach(inp => {
    if (inp) inp.addEventListener('input', updateFormulaPreview);
  });

  // Schedule Conflict Validator
  const scheduleDay = document.getElementById('schedule-day');
  const scheduleStart = document.getElementById('schedule-start');
  const scheduleEnd = document.getElementById('schedule-end');
  const conflictBadge = document.getElementById('schedule-conflict-badge');

  function checkScheduleConflict() {
    if (!scheduleDay || !scheduleStart || !scheduleEnd || !conflictBadge) return;
    const day = scheduleDay.value;
    const start = scheduleStart.value;
    const end = scheduleEnd.value;

    if (!day || !start || !end) {
      conflictBadge.style.display = 'none';
      return;
    }

    if (start >= end) {
      conflictBadge.className = 'alert alert-danger';
      conflictBadge.innerText = 'Jam selesai harus lebih besar dari jam mulai.';
      conflictBadge.style.display = 'flex';
      return;
    }

    // Compare with embedded existing schedules if available
    const existing = window.existingSchedules || [];
    let hasConflict = false;
    let conflictName = '';

    for (let s of existing) {
      if (s.day === day && s.status === 'active') {
        if (s.start_time < end && s.end_time > start) {
          hasConflict = true;
          conflictName = `${s.subject} (${s.start_time} - ${s.end_time})`;
          break;
        }
      }
    }

    if (hasConflict) {
      conflictBadge.className = 'alert alert-danger';
      conflictBadge.innerHTML = `⚠️ Jadwal bentrok dengan kelas <strong>${conflictName}</strong>.`;
      conflictBadge.style.display = 'flex';
    } else {
      conflictBadge.className = 'alert alert-success';
      conflictBadge.innerHTML = `✓ Jadwal tersedia dan tidak ada bentrok.`;
      conflictBadge.style.display = 'flex';
    }
  }

  [scheduleDay, scheduleStart, scheduleEnd].forEach(el => {
    if (el) el.addEventListener('change', checkScheduleConflict);
  });
});

// CBT Exam Helpers
function selectCBTOption(optionElement, questionId, optionLabel) {
  const container = optionElement.closest('.cbt-options-container');
  if (!container) return;
  container.querySelectorAll('.cbt-option-card').forEach(card => card.classList.remove('selected'));
  optionElement.classList.add('selected');

  const hiddenInput = document.getElementById(`answer-input-${questionId}`);
  if (hiddenInput) {
    hiddenInput.value = optionLabel;
  }

  // Update grid indicator
  const gridItem = document.getElementById(`grid-q-${questionId}`);
  if (gridItem) {
    gridItem.classList.add('answered');
  }
}

// Password Strength Indicator
function initPasswordStrength() {
  const passwordInput = document.getElementById('reg-password');
  const strengthContainer = document.getElementById('password-strength-container');
  if (!passwordInput || !strengthContainer) return;

  passwordInput.addEventListener('input', function() {
    const val = this.value;
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const bars = strengthContainer.querySelectorAll('.password-strength-bar');
    const textEl = strengthContainer.querySelector('.password-strength-text');
    const levels = ['', 'weak', 'weak', 'medium', 'strong', 'strong'];
    const labels = ['', 'Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
    const colors = ['', '#EF4444', '#EF4444', '#D9822B', '#4CAF50', '#1B5E2E'];

    bars.forEach((bar, i) => {
      bar.className = 'password-strength-bar';
      if (i < score) bar.classList.add(levels[score]);
    });

    if (textEl) {
      textEl.textContent = val.length > 0 ? labels[score] : '';
      textEl.style.color = colors[score];
    }

    strengthContainer.style.display = val.length > 0 ? 'block' : 'none';
  });
}

// Auto-dismiss alerts after 5 seconds
function initAlertDismiss() {
  document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
      alert.classList.add('dismissing');
      setTimeout(() => alert.remove(), 300);
    }, 5000);
  });
}

// Minimal Line Chart (Canvas API)
function drawLineChart(canvasId, dataPoints, labels) {
  const canvas = document.getElementById(canvasId);
  if (!canvas || !dataPoints || dataPoints.length === 0) return;

  const ctx = canvas.getContext('2d');
  const dpr = window.devicePixelRatio || 1;
  const rect = canvas.parentElement.getBoundingClientRect();
  canvas.width = rect.width * dpr;
  canvas.height = rect.height * dpr;
  canvas.style.width = rect.width + 'px';
  canvas.style.height = rect.height + 'px';
  ctx.scale(dpr, dpr);

  const W = rect.width;
  const H = rect.height;
  const padL = 36, padR = 12, padT = 16, padB = 28;
  const chartW = W - padL - padR;
  const chartH = H - padT - padB;

  const maxVal = Math.max(...dataPoints, 100);
  const minVal = Math.min(...dataPoints, 0);
  const range = maxVal - minVal || 1;

  // Grid lines
  ctx.strokeStyle = '#E8E4DC';
  ctx.lineWidth = 0.5;
  for (let i = 0; i <= 4; i++) {
    const y = padT + (chartH / 4) * i;
    ctx.beginPath();
    ctx.moveTo(padL, y);
    ctx.lineTo(W - padR, y);
    ctx.stroke();

    // Y-axis labels
    ctx.fillStyle = '#94A3B8';
    ctx.font = '10px Plus Jakarta Sans';
    ctx.textAlign = 'right';
    const val = Math.round(maxVal - (range / 4) * i);
    ctx.fillText(val, padL - 6, y + 3);
  }

  // Points and lines
  const points = dataPoints.map((val, i) => ({
    x: padL + (chartW / (dataPoints.length - 1 || 1)) * i,
    y: padT + chartH - ((val - minVal) / range) * chartH
  }));

  // Gradient fill
  const grad = ctx.createLinearGradient(0, padT, 0, H - padB);
  grad.addColorStop(0, 'rgba(30, 34, 56, 0.12)');
  grad.addColorStop(1, 'rgba(30, 34, 56, 0.01)');

  ctx.beginPath();
  ctx.moveTo(points[0].x, H - padB);
  points.forEach(p => ctx.lineTo(p.x, p.y));
  ctx.lineTo(points[points.length - 1].x, H - padB);
  ctx.closePath();
  ctx.fillStyle = grad;
  ctx.fill();

  // Line
  ctx.beginPath();
  ctx.strokeStyle = '#1E2238';
  ctx.lineWidth = 2;
  ctx.lineJoin = 'round';
  ctx.lineCap = 'round';
  points.forEach((p, i) => i === 0 ? ctx.moveTo(p.x, p.y) : ctx.lineTo(p.x, p.y));
  ctx.stroke();

  // Dots
  points.forEach((p, i) => {
    ctx.beginPath();
    ctx.arc(p.x, p.y, 3.5, 0, Math.PI * 2);
    ctx.fillStyle = '#1E2238';
    ctx.fill();
    ctx.beginPath();
    ctx.arc(p.x, p.y, 1.5, 0, Math.PI * 2);
    ctx.fillStyle = '#FFFFFF';
    ctx.fill();
  });

  // X-axis labels
  if (labels && labels.length) {
    ctx.fillStyle = '#94A3B8';
    ctx.font = '10px Plus Jakarta Sans';
    ctx.textAlign = 'center';
    points.forEach((p, i) => {
      if (labels[i]) ctx.fillText(labels[i], p.x, H - 6);
    });
  }
}

// Initialize enhancements
document.addEventListener('DOMContentLoaded', () => {
  initPasswordStrength();
  initAlertDismiss();
});

