// KU — 空 | Core Application Logic
// OGIS 2026 Competition Prototype

// Global State
const state = {
  isWindowActive: false,
  currentTime: new Date(2026, 4, 12, 19, 45, 0), // Simulated start: Tuesday 7:45 PM
  countdownSeconds: 15 * 60, // 15 minutes countdown (simulated fast)
  cognitiveScore: 34,
  queuedNotificationCount: 0,
  notificationsHeldThisWindow: 0,
  totalHeldHistorical: 1402,
  currentPersona: 'student', // 'student' or 'faculty'
  emergencyContacts: [
    { name: 'Mom', type: 'Family' },
    { name: 'Dr. Miller (On-Call)', type: 'Medical' }
  ],
  coordinationNotes: [
    { text: 'Finalized chemistry equations for lab. Check them over after the window.', group: 'Chemistry Lab 3', author: 'Alex K.', time: '7:42 PM', released: true },
    { text: 'Calculus homework PDF uploaded to drive.', group: 'Calculus Study Circle', author: 'Elena R.', time: '7:40 PM', released: true }
  ],
  activeRecoveryTimer: null,
  recoveryTimeLeft: 0,
  isAudioMuted: false,
  audioContext: null,
  synthNode: null,
  filterNode: null,
  lfoNode: null
};

// Mock Notification Database
const mockNotificationSenders = [
  { name: 'Canvas LMS', app: 'lms', message: 'New announcement in Intro to AI: Homework 3 grades published.' },
  { name: 'Prof. Davis', app: 'email', message: 'Urgent: Revision to Syllabus requirements for Lab 4.' },
  { name: 'Discord (Group Chat)', app: 'social', message: 'Did anyone figure out question 5? The deadline is midnight!' },
  { name: 'Instagram', app: 'social', message: 'Lucas tagged you in a post.' },
  { name: 'Slack (Study Group)', app: 'social', message: 'Meeting tomorrow at 9 AM in library room 302.' },
  { name: 'Canvas LMS', app: 'lms', message: 'Upcoming Deadline: Physics Assignment due in 4 hours.' },
  { name: 'Gmail', app: 'email', message: 'Registrar: Tuition statement updates for Summer 2026.' }
];

// Initialize the Platform
document.addEventListener('DOMContentLoaded', () => {
  renderCalendar();
  renderOptimizerChart();
  updateTimeDisplay();
  renderEmergencyContacts();
  renderCoordinationNotes();
  
  // Start simulation loops
  setInterval(simulateTimeProgress, 1000);
  setInterval(triggerRandomNotification, 12000);
  
  // Setup SVG Chart Hover Tooltip
  setupChartTooltip();
});

// Gateway Transition
function enterPlatform() {
  const gateway = document.getElementById('gatewayScreen');
  gateway.classList.add('fade-out');
  
  // Initialize Web Audio on user gesture
  initAudioEngine();
}

// ----------------------------------------------------
// TIME & SIMULATION ENGINES
// ----------------------------------------------------

function updateTimeDisplay() {
  const timeString = state.currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  document.getElementById('phoneTime').innerText = timeString;
  
  // Calculate window countdown
  if (!state.isWindowActive) {
    if (state.countdownSeconds > 0) {
      state.countdownSeconds--;
    } else {
      // Auto trigger window when countdown ends
      toggleSynchronizedWindow(true);
    }
    
    // Format countdown label
    const hours = Math.floor(state.countdownSeconds / 3600);
    const minutes = Math.floor((state.countdownSeconds % 3600) / 60);
    const secs = state.countdownSeconds % 60;
    
    const formattedCountdown = `${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}m ${secs.toString().padStart(2, '0')}s`;
    document.getElementById('nextWindowTimer').innerText = formattedCountdown;
  }
}

function simulateTimeProgress() {
  // Progress time: 1 simulated minute per second to make countdowns feel lively
  state.currentTime.setMinutes(state.currentTime.getMinutes() + 1);
  updateTimeDisplay();
  
  // Update focus timer if active
  if (state.isWindowActive && state.recoveryTimeLeft > 0) {
    state.recoveryTimeLeft--;
    const displayMin = Math.floor(state.recoveryTimeLeft / 60).toString().padStart(2, '0');
    const displaySec = (state.recoveryTimeLeft % 60).toString().padStart(2, '0');
    document.getElementById('focusTimerDisplay').innerText = `${displayMin}:${displaySec}`;
    
    // Simulate breathing text adjustments based on a 7-second cycle
    const breatheCycle = state.recoveryTimeLeft % 7;
    const breatherText = document.getElementById('breatherText');
    if (breatheCycle >= 4) {
      breatherText.innerText = "Exhale";
    } else if (breatheCycle >= 3) {
      breatherText.innerText = "Hold";
    } else {
      breatherText.innerText = "Inhale";
    }

    if (state.recoveryTimeLeft === 0) {
      concludeSynchronizedWindow();
    }
  }
}

// ----------------------------------------------------
// NOTIFICATION INTERCEPT & RELEASE ENGINE
// ----------------------------------------------------

function triggerRandomNotification() {
  // If focus mode active, do not generate *normal* notifications, except maybe emergency bypasses
  if (state.isWindowActive) {
    // 25% chance of generating an emergency contact bypass message
    if (Math.random() < 0.25 && state.emergencyContacts.length > 0) {
      const contact = state.emergencyContacts[Math.floor(Math.random() * state.emergencyContacts.length)];
      createBypassNotification(contact.name, "Urgent: " + getEmergencyMessage(contact.name));
    } else {
      // Notification is queued at server level
      state.notificationsHeldThisWindow++;
      state.totalHeldHistorical++;
      document.getElementById('statsNotifHeld').innerText = state.totalHeldHistorical.toLocaleString();
      document.getElementById('statsNotifQueuedRate').innerText = `${state.notificationsHeldThisWindow} queued currently`;
      document.getElementById('statsNotifQueuedRate').className = "stat-trend trend-down"; // highlighted red/yellow during hold
    }
    return;
  }
  
  // Otherwise, push normal notifications
  const notif = mockNotificationSenders[Math.floor(Math.random() * mockNotificationSenders.length)];
  addNotificationToFeed(notif.name, notif.message, notif.app, false);
  
  // Connectivity load reduces Cognitive Score
  adjustCognitiveScore(-Math.floor(Math.random() * 3 + 1));
}

function getEmergencyMessage(name) {
  const msgs = [
    "Are you home yet?",
    "Dinner is ready, call me.",
    "Bypass trigger test. Everything is fine.",
    "Appointment scheduled for tomorrow morning."
  ];
  return msgs[Math.floor(Math.random() * msgs.length)];
}

function addNotificationToFeed(sender, message, app, isBypass = false) {
  const feed = document.getElementById('phoneNotificationFeed');
  
  // Limit to 4 visible notifications to prevent scrolling container overflow
  if (feed.children.length >= 4) {
    feed.removeChild(feed.lastChild);
  }
  
  const notifCard = document.createElement('div');
  notifCard.className = `mock-notification ${isBypass ? 'bypass-active' : ''}`;
  if (isBypass) {
    notifCard.style.border = "1px solid var(--color-danger)";
    notifCard.style.background = "rgba(239, 68, 68, 0.08)";
  }
  
  let appLetter = app.charAt(0).toUpperCase();
  
  notifCard.innerHTML = `
    <div class="notif-app-icon ${app}">${appLetter}</div>
    <div class="notif-body">
      <div class="notif-title">
        <span style="${isBypass ? 'color: var(--color-danger); font-weight:700;' : ''}">${sender} ${isBypass ? '🚨 [BYPASS]' : ''}</span>
        <span class="notif-time">Just now</span>
      </div>
      <div class="notif-text">${message}</div>
    </div>
  `;
  
  feed.insertBefore(notifCard, feed.firstChild);
}

function createBypassNotification(sender, message) {
  // Bypass notification creates card even during focus window, illustrating the architecture
  addNotificationToFeed(sender, message, 'email', true);
  
  // Emergency beep synth triggers briefly
  playEmergencyBeep();
}

function releaseQueuedNotifications() {
  const feed = document.getElementById('phoneNotificationFeed');
  feed.innerHTML = ''; // Clear feed
  
  // Create simultaneous release messages (Maria's batch delivery)
  const announcements = [
    { sender: 'Canvas LMS', msg: 'System Policy: Batch release of 4 notification payloads.', app: 'lms' },
    { sender: 'Discord (Study Group)', msg: 'Project group chat reactivated.', app: 'social' },
    { sender: 'Prof. Davis (Email)', msg: 'Scheduled delivery: Readings for next lecture uploaded.', app: 'email' }
  ];
  
  announcements.forEach((a, index) => {
    setTimeout(() => {
      addNotificationToFeed(a.sender, a.msg, a.app, false);
    }, index * 250);
  });
}

// ----------------------------------------------------
// COGNITIVE RECOVERY METER
// ----------------------------------------------------

function adjustCognitiveScore(delta) {
  state.cognitiveScore = Math.max(5, Math.min(100, state.cognitiveScore + delta));
  
  const circle = document.getElementById('cogScoreCircle');
  const scoreVal = document.getElementById('cogScoreVal');
  const statusDesc = document.getElementById('cogScoreStatus');
  
  // Circle circumference is 440 (2 * pi * r where r=70 is 439.8)
  const offset = 440 - (state.cognitiveScore / 100) * 440;
  circle.style.strokeDashoffset = offset;
  scoreVal.innerText = `${state.cognitiveScore}%`;
  
  // Update status labels
  if (state.cognitiveScore < 40) {
    statusDesc.className = "cog-status-desc depleted";
    statusDesc.innerHTML = "Capacity: <span>Drained</span>";
    circle.style.stroke = "url(#cogGradient)";
  } else if (state.cognitiveScore < 75) {
    statusDesc.className = "cog-status-desc restored";
    statusDesc.style.color = "var(--color-warning)";
    statusDesc.innerHTML = "Capacity: <span>Recovering</span>";
    circle.style.stroke = "var(--color-warning)";
  } else {
    statusDesc.className = "cog-status-desc restored";
    statusDesc.style.color = "var(--color-accent)";
    statusDesc.innerHTML = "Capacity: <span>Deep Focus Ready</span>";
    circle.style.stroke = "var(--color-accent)";
  }
}

// ----------------------------------------------------
// SYNCHRONIZED WINDOW MODALS & CONTROL
// ----------------------------------------------------

function toggleSynchronizedWindow(autoTrigger = false) {
  if (state.isWindowActive) {
    concludeSynchronizedWindow();
  } else {
    startSynchronizedWindow();
  }
}

function startSynchronizedWindow() {
  state.isWindowActive = true;
  state.notificationsHeldThisWindow = 0;
  
  // Update System Badges
  const dot = document.getElementById('systemStatusDot');
  const text = document.getElementById('systemStatusText');
  dot.className = "status-dot window-active";
  text.innerText = "Synchronized Window Active";
  text.style.color = "var(--color-secondary)";
  
  // Update Admin Buttons
  const btn = document.getElementById('btnTriggerSync');
  btn.className = "btn-trigger-window active";
  document.getElementById('btnSyncIcon').innerText = "🌿";
  document.getElementById('btnSyncText').innerText = "Conclude Window";
  
  // Update Notification Stats Pill
  document.getElementById('statsNotifQueuedRate').innerText = "0 queued currently";
  document.getElementById('statsNotifQueuedRate').className = "stat-trend trend-down";
  
  // Disable normal scheduler countdown display
  document.getElementById('nextWindowTimer').innerText = "IN PROGRESS";
  document.getElementById('nextWindowTimer').style.color = "var(--color-secondary)";
  
  // Active QoS Indicators
  updatePolicyDisplay(true);
  
  // Highlight active window in schedule
  highlightCalendarWindow(true);
  
  // Initiate student focus mode (Default Slow Reading 20m)
  startGuidedRecovery('Analog Slow Reading', '20:00', 'Read a physical book or analog article from your course reading list. No screen backlight.', '📚');
}

function concludeSynchronizedWindow() {
  state.isWindowActive = false;
  
  // Reset System Badges
  const dot = document.getElementById('systemStatusDot');
  const text = document.getElementById('systemStatusText');
  dot.className = "status-dot active";
  text.innerText = "Standard Connectivity";
  text.style.color = "var(--text-primary)";
  
  // Reset Admin Buttons
  const btn = document.getElementById('btnTriggerSync');
  btn.className = "btn-trigger-window";
  document.getElementById('btnSyncIcon').innerText = "🧘";
  document.getElementById('btnSyncText').innerText = "Simulate KU Window";
  
  // Reset Countdown Clock
  state.countdownSeconds = 74 * 60; // reset to 1h 14m
  document.getElementById('nextWindowTimer').style.color = "var(--color-secondary)";
  
  // Update Policy display
  updatePolicyDisplay(false);
  
  // Highlight calendar off
  highlightCalendarWindow(false);
  
  // Fade focus mode phone screen out
  document.getElementById('focusModeScreen').classList.remove('active');
  document.getElementById('phoneFocusIcon').style.display = 'none';
  document.getElementById('notifStatusText').innerText = "Standard";
  document.getElementById('notifStatusText').style.color = "var(--color-primary)";
  
  // Release queued notifications in a wave
  releaseQueuedNotifications();
  
  // Adjust Maria's score to recovered state
  adjustCognitiveScore(51); // 34% -> 85%
  
  // Update queued display on admin portal
  document.getElementById('statsNotifQueuedRate').innerText = `${state.notificationsHeldThisWindow} released simultaneously`;
  document.getElementById('statsNotifQueuedRate').className = "stat-trend trend-up";
  
  // Release any drafted sticky notes
  releaseDraftedNotes();
  
  // Turn off Synthesizer
  stopSynth();
}

function startGuidedRecovery(title, duration, description, icon) {
  // Open Focus Screen inside simulator
  document.getElementById('focusActivityTitle').innerText = title;
  document.getElementById('focusActivityDesc').innerText = description;
  document.getElementById('focusActivitySub').innerText = `Recovery mode: ${title}`;
  
  // Parse Duration
  const min = parseInt(duration.split(':')[0]);
  state.recoveryTimeLeft = min * 60; // normal seconds (simulated minutes in simulator)
  // Let's set it to 30 simulated seconds for quick presentation testing if it's slow
  state.recoveryTimeLeft = 30; 
  
  document.getElementById('focusTimerDisplay').innerText = duration;
  document.getElementById('focusModeScreen').classList.add('active');
  document.getElementById('phoneFocusIcon').style.display = 'inline';
  document.getElementById('notifStatusText').innerText = "Suppressed";
  document.getElementById('notifStatusText').style.color = "var(--color-secondary)";
  
  // Turn on Synthesizer if not muted
  if (!state.isAudioMuted) {
    playSynth();
  }
}

function emergencyBypass() {
  // Allows student to manually break window if needed (but recorded/penalized by cognitive drop)
  concludeSynchronizedWindow();
  adjustCognitiveScore(-15); // breaking window drops depth score significantly due to hyperconnected shock
}

function updatePolicyDisplay(isActive) {
  const qosCheck = document.getElementById('policyQoS');
  const emailCheck = document.getElementById('policyEmailHold');
  const autoCheck = document.getElementById('policyAutoReply');
  
  if (isActive) {
    if (qosCheck.checked) {
      document.getElementById('policyQoS').parentElement.parentElement.style.borderColor = "var(--color-secondary)";
    }
    if (emailCheck.checked) {
      document.getElementById('policyEmailHold').parentElement.parentElement.style.borderColor = "var(--color-secondary)";
    }
    if (autoCheck.checked) {
      document.getElementById('policyAutoReply').parentElement.parentElement.style.borderColor = "var(--color-secondary)";
    }
  } else {
    document.querySelectorAll('.policy-card').forEach(c => {
      c.style.borderColor = "var(--glass-border)";
    });
  }
}

// ----------------------------------------------------
// PERSONA NAVIGATION
// ----------------------------------------------------

function switchPhonePersona(persona) {
  state.currentPersona = persona;
  
  const tabStudent = document.getElementById('tabStudent');
  const tabFaculty = document.getElementById('tabFaculty');
  const viewStudent = document.getElementById('viewStudent');
  const viewFaculty = document.getElementById('viewFaculty');
  
  if (persona === 'student') {
    tabStudent.classList.add('active');
    tabFaculty.classList.remove('active');
    viewStudent.classList.add('active');
    viewFaculty.classList.remove('active');
  } else {
    tabStudent.classList.remove('active');
    tabFaculty.classList.add('active');
    viewStudent.classList.remove('active');
    viewFaculty.classList.add('active');
  }
}

// Phone Overlay Controls (Settings screens)
function openPhoneOverlay(id) {
  document.getElementById(id).classList.add('active');
}

function closePhoneOverlay(id) {
  document.getElementById(id).classList.remove('active');
}

// ----------------------------------------------------
// EMERGENCY BYPASS MANAGEMENT
// ----------------------------------------------------

function renderEmergencyContacts() {
  const container = document.getElementById('emergencyContactsList');
  container.innerHTML = '';
  state.emergencyContacts.forEach((contact, idx) => {
    const chip = document.createElement('div');
    chip.className = 'contact-chip';
    chip.innerHTML = `
      <span>📞 <strong>${contact.name}</strong> (${contact.type})</span>
      <button class="delete-btn" onclick="deleteEmergencyContact(${idx})">×</button>
    `;
    container.appendChild(chip);
  });
}

function addEmergencyContact() {
  const nameInput = document.getElementById('contactNameInput');
  const typeSelect = document.getElementById('contactTypeSelect');
  
  if (nameInput.value.trim() === '') return;
  
  state.emergencyContacts.push({
    name: nameInput.value.trim(),
    type: typeSelect.value
  });
  
  nameInput.value = '';
  renderEmergencyContacts();
}

function deleteEmergencyContact(idx) {
  state.emergencyContacts.splice(idx, 1);
  renderEmergencyContacts();
}

// ----------------------------------------------------
// GROUP COORDINATION PINBOARD
// ----------------------------------------------------

function renderCoordinationNotes() {
  const container = document.getElementById('coordinationNotesGrid');
  container.innerHTML = '';
  
  state.coordinationNotes.forEach((note) => {
    const card = document.createElement('div');
    // Assign color based on group
    let colorClass = 'yellow';
    if (note.group.includes('Calculus')) colorClass = 'blue';
    if (note.group.includes('Chemistry')) colorClass = 'green';
    
    card.className = `sticky-note ${colorClass}`;
    card.innerHTML = `
      <div>
        <div style="font-weight:700; margin-bottom: 2px;">${note.group}</div>
        <div>${note.text}</div>
        ${!note.released ? '<div style="font-style:italic; font-size:0.55rem; color:#b45309; margin-top:2px;">[Draft: Releases post-window]</div>' : ''}
      </div>
      <div class="note-author">— ${note.author} (${note.time})</div>
    `;
    container.appendChild(card);
  });
}

function addCoordinationNote() {
  const txtInput = document.getElementById('coordinationNoteInput');
  const groupSelect = document.getElementById('coordinationGroupSelect');
  
  if (txtInput.value.trim() === '') return;
  
  const timeString = state.currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  
  state.coordinationNotes.push({
    text: txtInput.value.trim(),
    group: groupSelect.value,
    author: state.currentPersona === 'student' ? 'Maria K.' : 'Prof. Santos',
    time: timeString,
    released: !state.isWindowActive // If window is active, note is drafted
  });
  
  txtInput.value = '';
  renderCoordinationNotes();
}

function releaseDraftedNotes() {
  state.coordinationNotes.forEach(n => {
    n.released = true;
  });
  renderCoordinationNotes();
}

// Disconnection Accommodation Submission
function submitAccommodation() {
  const category = document.getElementById('accommodationCategory').value;
  alert(`Disconnection Accommodation protocol submitted under category: ${category}. Your request has been encrypted and routed to the Academic Registry.`);
  closePhoneOverlay('overlayAccommodation');
}

// ----------------------------------------------------
// CALENDAR SYSTEM
// ----------------------------------------------------

const weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

function renderCalendar() {
  const container = document.getElementById('calendarWeekGrid');
  container.innerHTML = '';
  
  weekdays.forEach((day, idx) => {
    const isToday = (day === 'Tue'); // Simulating Tuesday
    const dayCard = document.createElement('div');
    dayCard.className = `calendar-day ${isToday ? 'today' : ''}`;
    
    let eventsHTML = '';
    if (day === 'Tue') {
      eventsHTML = `<div class="calendar-event" id="tueEvent">8:00 PM - 9:00 PM</div>`;
    } else if (day === 'Thu') {
      eventsHTML = `<div class="calendar-event">8:00 PM - 9:00 PM</div>`;
    } else if (day === 'Sun') {
      eventsHTML = `<div class="calendar-event">2:00 PM - 5:00 PM</div>`;
    }
    
    dayCard.innerHTML = `
      <div class="day-header">
        <span class="day-name">${day}</span>
        <span class="day-number">${11 + idx}</span>
      </div>
      <div class="day-events">
        ${eventsHTML}
      </div>
    `;
    container.appendChild(dayCard);
  });
}

function highlightCalendarWindow(isActive) {
  const tueEv = document.getElementById('tueEvent');
  if (tueEv) {
    if (isActive) {
      tueEv.className = "calendar-event today-active";
      tueEv.innerText = "8:00 PM [ACTIVE]";
    } else {
      tueEv.className = "calendar-event";
      tueEv.innerText = "8:00 PM - 9:00 PM";
    }
  }
}

// ----------------------------------------------------
// AI OPTIMIZER CHART ENGINE (VANILLA SVG)
// ----------------------------------------------------

// Generating time-series curve showing university data traffic and highlight windows
const chartData = [
  { hour: 0, load: 32 }, { hour: 2, load: 18 }, { hour: 4, load: 8 },
  { hour: 6, load: 24 }, { hour: 8, load: 78 }, { hour: 10, load: 85 },
  { hour: 12, load: 92 }, { hour: 14, load: 88 }, { hour: 16, load: 95 },
  { hour: 18, load: 82 }, { hour: 20, load: 30 }, { hour: 22, load: 68 }
];

function renderOptimizerChart() {
  const svg = document.getElementById('optimizationChart');
  svg.innerHTML = ''; // Clear previous contents
  
  // Definitions (Gradients)
  const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
  defs.innerHTML = `
    <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="var(--color-primary)" stop-opacity="0.3"/>
      <stop offset="100%" stop-color="var(--color-primary)" stop-opacity="0.0"/>
    </linearGradient>
  `;
  svg.appendChild(defs);
  
  const width = 760;
  const height = 220;
  const paddingLeft = 40;
  const paddingRight = 20;
  const paddingTop = 20;
  const paddingBottom = 30;
  
  const plotWidth = width - paddingLeft - paddingRight;
  const plotHeight = height - paddingTop - paddingBottom;
  
  // Draw Grid Lines (Y-axis grid)
  const gridLevels = [0, 25, 50, 75, 100];
  gridLevels.forEach(level => {
    const y = paddingTop + plotHeight * (1 - level / 100);
    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    line.setAttribute('x1', paddingLeft);
    line.setAttribute('y1', y);
    line.setAttribute('x2', width - paddingRight);
    line.setAttribute('y2', y);
    line.setAttribute('class', 'chart-grid-line');
    svg.appendChild(line);
    
    // Label
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', paddingLeft - 10);
    text.setAttribute('y', y + 3);
    text.setAttribute('text-anchor', 'end');
    text.setAttribute('class', 'chart-axis-label');
    text.textContent = `${level}%`;
    svg.appendChild(text);
  });
  
  // Draw X-axis Labels (Hours)
  chartData.forEach(d => {
    const x = paddingLeft + (d.hour / 24) * plotWidth;
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', x);
    text.setAttribute('y', height - 10);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('class', 'chart-axis-label');
    text.textContent = `${d.hour}:00`;
    svg.appendChild(text);
  });
  
  // Shaded Synchronized Windows Areas
  // 1st window: 4 AM - 5 AM
  // 2nd window: 8 PM - 9 PM (Hour 20 to 21)
  const windowRanges = [{ start: 4, end: 5.5, label: 'Early-birds Rest' }, { start: 20, end: 21.5, label: 'Evening Silence' }];
  windowRanges.forEach(w => {
    const x1 = paddingLeft + (w.start / 24) * plotWidth;
    const x2 = paddingLeft + (w.end / 24) * plotWidth;
    
    const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
    rect.setAttribute('x', x1);
    rect.setAttribute('y', paddingTop);
    rect.setAttribute('width', x2 - x1);
    rect.setAttribute('height', plotHeight);
    rect.setAttribute('class', 'chart-window-area');
    svg.appendChild(rect);
    
    // Active Window Text
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', x1 + (x2 - x1)/2);
    text.setAttribute('y', paddingTop + 15);
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('fill', 'var(--color-secondary)');
    text.setAttribute('font-size', '8px');
    text.setAttribute('font-weight', '700');
    text.textContent = w.label;
    svg.appendChild(text);
  });
  
  // Build Line Path
  let pathD = '';
  let areaD = `M ${paddingLeft} ${height - paddingBottom}`;
  
  chartData.forEach((d, idx) => {
    const x = paddingLeft + (d.hour / 24) * plotWidth;
    const y = paddingTop + plotHeight * (1 - d.load / 100);
    
    if (idx === 0) {
      pathD += `M ${x} ${y}`;
    } else {
      // Smooth curve calculation using bezier control points
      const prevX = paddingLeft + (chartData[idx - 1].hour / 24) * plotWidth;
      const prevY = paddingTop + plotHeight * (1 - chartData[idx - 1].load / 100);
      const cpX1 = prevX + (x - prevX) / 2;
      const cpY1 = prevY;
      const cpX2 = prevX + (x - prevX) / 2;
      const cpY2 = y;
      pathD += ` C ${cpX1} ${cpY1}, ${cpX2} ${cpY2}, ${x} ${y}`;
    }
    
    areaD += ` L ${x} ${y}`;
  });
  
  areaD += ` L ${paddingLeft + (22 / 24) * plotWidth} ${height - paddingBottom} Z`;
  
  // Add Area Path (fill)
  const areaPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
  areaPath.setAttribute('d', areaD);
  areaPath.setAttribute('fill', 'url(#chartGradient)');
  svg.appendChild(areaPath);
  
  // Add Line Path
  const linePath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
  linePath.setAttribute('d', pathD);
  linePath.setAttribute('class', 'chart-line');
  svg.appendChild(linePath);
  
  // Add Interactive Dots
  chartData.forEach(d => {
    const x = paddingLeft + (d.hour / 24) * plotWidth;
    const y = paddingTop + plotHeight * (1 - d.load / 100);
    
    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circle.setAttribute('cx', x);
    circle.setAttribute('cy', y);
    circle.setAttribute('r', '4');
    circle.setAttribute('fill', 'var(--bg-secondary)');
    circle.setAttribute('stroke', 'var(--color-primary)');
    circle.setAttribute('stroke-width', '2');
    circle.setAttribute('style', 'cursor:pointer;');
    
    // Hover events
    circle.addEventListener('mouseover', (e) => {
      circle.setAttribute('r', '6');
      circle.setAttribute('stroke', 'var(--color-secondary)');
      showChartTooltip(e, d.hour, d.load);
    });
    
    circle.addEventListener('mouseout', () => {
      circle.setAttribute('r', '4');
      circle.setAttribute('stroke', 'var(--color-primary)');
      hideChartTooltip();
    });
    
    svg.appendChild(circle);
  });
}

function setupChartTooltip() {
  const tooltip = document.getElementById('chartTooltip');
  tooltip.style.display = 'none';
}

function showChartTooltip(event, hour, load) {
  const tooltip = document.getElementById('chartTooltip');
  const container = document.querySelector('.chart-container');
  const rect = container.getBoundingClientRect();
  
  const x = event.clientX - rect.left + 15;
  const y = event.clientY - rect.top - 40;
  
  tooltip.style.left = `${x}px`;
  tooltip.style.top = `${y}px`;
  tooltip.style.display = 'block';
  
  let isWindow = (hour >= 4 && hour <= 6) || (hour >= 20 && hour <= 22);
  
  tooltip.innerHTML = `
    <strong>${hour}:00</strong><br/>
    LMS Traffic load: <span style="color:var(--color-secondary); font-weight:700;">${load}%</span><br/>
    ${isWindow ? '🌿 <span style="color:var(--color-accent); font-weight:bold;">Synchronized Window</span>' : '⚡ High Connectivity'}
  `;
}

function hideChartTooltip() {
  document.getElementById('chartTooltip').style.display = 'none';
}

// ----------------------------------------------------
// WEB AUDIO API - CALMING DRONE SYNTHESIZER
// ----------------------------------------------------

function initAudioEngine() {
  // Wait for user interaction to resume
  window.AudioContext = window.AudioContext || window.webkitAudioContext;
}

function playSynth() {
  if (state.isAudioMuted) return;
  
  try {
    if (!state.audioContext) {
      state.audioContext = new AudioContext();
    }
    
    if (state.audioContext.state === 'suspended') {
      state.audioContext.resume();
    }
    
    // Create base low-pass filter
    state.filterNode = state.audioContext.createBiquadFilter();
    state.filterNode.type = 'lowpass';
    state.filterNode.frequency.setValueAtTime(220, state.audioContext.currentTime); // calming cutoff
    state.filterNode.Q.setValueAtTime(1, state.audioContext.currentTime);
    
    // Create primary deep drone oscillator (C2 ~65.4 Hz)
    const osc1 = state.audioContext.createOscillator();
    osc1.type = 'sawtooth';
    osc1.frequency.setValueAtTime(65.4, state.audioContext.currentTime);
    
    // Create harmonic oscillator (G2 ~98.0 Hz) detuned slightly
    const osc2 = state.audioContext.createOscillator();
    osc2.type = 'triangle';
    osc2.frequency.setValueAtTime(98.1, state.audioContext.currentTime);
    
    // Create third soft oscillator (C3 ~130.8 Hz)
    const osc3 = state.audioContext.createOscillator();
    osc3.type = 'sine';
    osc3.frequency.setValueAtTime(130.8, state.audioContext.currentTime);
    
    // Gain Node for Volume & Envelope
    state.synthNode = state.audioContext.createGain();
    state.synthNode.gain.setValueAtTime(0, state.audioContext.currentTime);
    // Smooth ramp in (fade in)
    state.synthNode.gain.linearRampToValueAtTime(0.25, state.audioContext.currentTime + 3);
    
    // Connect oscillators to filter, and filter to gain
    osc1.connect(state.filterNode);
    osc2.connect(state.filterNode);
    osc3.connect(state.filterNode);
    state.filterNode.connect(state.synthNode);
    state.synthNode.connect(state.audioContext.destination);
    
    // LFO (Low Frequency Oscillator) to modulate filter cutoff to simulate slow breathing swells
    state.lfoNode = state.audioContext.createOscillator();
    state.lfoNode.type = 'sine';
    state.lfoNode.frequency.setValueAtTime(0.14, state.audioContext.currentTime); // ~7 seconds cycle (breathe in/out)
    
    const lfoGain = state.audioContext.createGain();
    lfoGain.gain.setValueAtTime(80, state.audioContext.currentTime); // swing cutoff between 140Hz and 300Hz
    
    state.lfoNode.connect(lfoGain);
    lfoGain.connect(state.filterNode.frequency);
    
    // Start nodes
    osc1.start();
    osc2.start();
    osc3.start();
    state.lfoNode.start();
    
    // Keep reference to oscillators for cleaning
    state.activeOscillators = [osc1, osc2, osc3];
    
  } catch (error) {
    console.error("Web Audio API failed to load", error);
  }
}

function stopSynth() {
  if (state.synthNode && state.audioContext) {
    try {
      state.synthNode.gain.linearRampToValueAtTime(0, state.audioContext.currentTime + 0.5);
      setTimeout(() => {
        if (state.activeOscillators) {
          state.activeOscillators.forEach(osc => osc.stop());
        }
        if (state.lfoNode) state.lfoNode.stop();
        state.synthNode = null;
        state.lfoNode = null;
      }, 600);
    } catch (e) {
      console.warn(e);
    }
  }
}

function playEmergencyBeep() {
  if (state.isAudioMuted) return;
  try {
    const ctx = state.audioContext || new AudioContext();
    const osc = ctx.createOscillator();
    const gain = ctx.createGain();
    
    osc.type = 'sine';
    osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5 pitch
    
    gain.gain.setValueAtTime(0.1, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
    
    osc.connect(gain);
    gain.connect(ctx.destination);
    
    osc.start();
    osc.stop(ctx.currentTime + 0.4);
  } catch (e) {
    console.warn("Bypass sound fail", e);
  }
}

function toggleAmbientSound() {
  state.isAudioMuted = !state.isAudioMuted;
  const emoji = document.getElementById('soundStatusEmoji');
  const txt = document.getElementById('soundStatusText');
  
  if (state.isAudioMuted) {
    emoji.innerText = "🔇";
    txt.innerText = "Sound Muted";
    stopSynth();
  } else {
    emoji.innerText = "🔊";
    txt.innerText = "Calming Drone Sound";
    if (state.isWindowActive) {
      playSynth();
    }
  }
}
