import './bootstrap';

// Signup Form Validation Functions

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

/**
 * Toggle confirm password visibility
 */
function toggleConfirmPasswordVisibility(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

/**
 * Validate signup form
 * Enables submit button only when all fields are filled and checkbox is checked
 */
function validateForm() {
    const nameInput = document.getElementById('nameInput');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput');
    const confirmPasswordInput = document.getElementById('confirmPasswordInput');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const submitBtn = document.getElementById('submitBtn');

    // Check if all fields are filled
    const allFieldsFilled = nameInput.value.trim() !== '' &&
                           emailInput.value.trim() !== '' &&
                           passwordInput.value.trim() !== '' &&
                           confirmPasswordInput.value.trim() !== '';

    // Check if checkbox is checked
    const checkboxChecked = termsCheckbox.checked;

    // Enable button only if all fields are filled and checkbox is checked
    if (allFieldsFilled && checkboxChecked) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Make functions globally available
window.togglePasswordVisibility = togglePasswordVisibility;
window.toggleConfirmPasswordVisibility = toggleConfirmPasswordVisibility;
window.validateForm = validateForm;

/**
 * Validate login form
 * Enables submit button only when email and password are filled
 */
function validateLoginForm() {
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput');
    const loginBtn = document.getElementById('loginBtn');

    // Check if all fields are filled
    const allFieldsFilled = emailInput.value.trim() !== '' &&
                           passwordInput.value.trim() !== '';

    // Enable button only if all fields are filled
    if (allFieldsFilled) {
        loginBtn.disabled = false;
    } else {
        loginBtn.disabled = true;
    }
}

window.validateLoginForm = validateLoginForm;

/**
 * Fetch all societies from API
 * Returns formatted JSON with society data
 */
async function fetchSocieties() {
    try {
        const response = await fetch('/api/societies', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        return data.societies || [];
    } catch (error) {
        console.error('Error fetching societies:', error);
        return [];
    }
}

/**
 * Fetch detailed society data including members
 * @param {number} societyID 
 */
async function fetchSocietyDetails(societyID) {
    try {
        const response = await fetch(`/api/societies/${societyID}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        return data.society || null;
    } catch (error) {
        console.error('Error fetching society details:', error);
        return null;
    }
}

/**
 * Fetch society committee members
 * @param {number} societyID 
 */
async function fetchSocietyCommittee(societyID) {
    try {
        const response = await fetch(`/api/societies/${societyID}/committee`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        return data.committee || [];
    } catch (error) {
        console.error('Error fetching committee:', error);
        return [];
    }
}

/**
 * Load all societies and populate the table
 * Called on page load for admin_society
 */
async function loadSocieties() {
    const tableBody = document.getElementById('societyTableBody');
    
    if (!tableBody) {
        console.warn('Society table body not found');
        return;
    }

    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="7" class="p-8 text-center">
                <div class="flex flex-col items-center gap-3">
                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">loading</span>
                    <p class="font-medium text-slate-500">Loading societies...</p>
                </div>
            </td>
        </tr>
    `;

    // // Fetch societies
    // const societies = await fetchSocieties();

    // if (societies.length === 0) {
    //     tableBody.innerHTML = `
    //         <tr>
    //             <td colspan="7" class="p-8 text-center text-slate-500 dark:text-slate-400">
    //                 <div class="flex flex-col items-center gap-3">
    //                     <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">groups</span>
    //                     <p class="font-medium">No societies found</p>
    //                     <p class="text-sm">Create your first society to get started.</p>
    //                 </div>
    //             </td>
    //         </tr>
    //     `;
    //     return;
    // }

    // Fetch detailed data for each society
    let html = '';
    for (const society of societies) {
        const details = await fetchSocietyDetails(society.id);
        
        if (!details) continue;

        const presidentName = details.president ? details.president.name : 'No President';
        const memberCount = society.memberCount || details.memberCount || 0;
        const status = details.isDelete ? 'Deleted' : 'Active';
        const statusClass = details.isDelete ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
        const photoUrl = details.photoPath ? `/storage/${details.photoPath}` : null;
        const initials = society.name.substring(0, 2).toUpperCase();

        html += `
            <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="p-4 text-center">
                    <input class="rounded border-slate-300 text-primary focus:ring-primary/50 bg-white dark:bg-slate-700 dark:border-slate-600" type="checkbox" />
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden shrink-0 border border-slate-100 dark:border-slate-700">
                            ${photoUrl ? `<img alt="${society.name} Logo" class="w-full h-full object-cover" src="${photoUrl}" />` : `<span class="text-sm font-bold text-blue-700">${initials}</span>`}
                        </div>
                        <div class="flex flex-col">
                            <span class="font-semibold text-slate-900 dark:text-white">${society.name}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Est. ${new Date(details.created_at || Date.now()).getFullYear()}</span>
                        </div>
                    </div>
                </td>
                <td class="p-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300">
                        ${details.joinType ? details.joinType.charAt(0).toUpperCase() + details.joinType.slice(1) : 'General'}
                    </span>
                </td>
                <td class="p-4">
                    <div class="flex items-center gap-2">
                        <div class="size-6 rounded-full bg-slate-200 overflow-hidden" style="background-image: url('https://ui-avatars.com/api/?name=${encodeURIComponent(presidentName)}'); background-size: cover;"></div>
                        <span class="text-slate-700 dark:text-slate-300">${presidentName}</span>
                    </div>
                </td>
                <td class="p-4 text-slate-600 dark:text-slate-400">${memberCount}</td>
                <td class="p-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ${statusClass} border ${status === 'Active' ? 'border-emerald-200 dark:border-emerald-800' : 'border-red-200 dark:border-red-800'}">
                        <span class="size-1.5 rounded-full ${status === 'Active' ? 'bg-emerald-500' : 'bg-red-500'}"></span>
                        ${status}
                    </span>
                </td>
                <td class="p-4 text-right">
                    <button class="text-slate-400 hover:text-primary transition-colors p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800">
                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                    </button>
                </td>
            </tr>
        `;
    }

    tableBody.innerHTML = html;
    
    // Update society count
    const countElement = document.getElementById('societyCount');
    const labelElement = document.getElementById('societyLabel');
    if (countElement) {
        countElement.textContent = societies.length;
    }
    if (labelElement) {
        labelElement.textContent = societies.length === 1 ? 'society' : 'societies';
    }
}

// Make functions globally available
window.fetchSocieties = fetchSocieties;
window.fetchSocietyDetails = fetchSocietyDetails;
window.loadSocieties = loadSocieties;

/**
 * Fetch all events from API
 * Returns formatted JSON with event data
 */
async function fetchEvents() {
    try {
        const response = await fetch('/api/events', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        return data.events || [];
    } catch (error) {
        console.error('Error fetching events:', error);
        return [];
    }
}

/**
 * Fetch event statistics
 */
async function fetchEventStats() {
    try {
        const response = await fetch('/api/events/stats/all', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        return data.data || { pendingApproval: 0, approved: 0, rejected: 0, upcoming: 0, total: 0 };
    } catch (error) {
        console.error('Error fetching event stats:', error);
        return { pendingApproval: 0, approved: 0, rejected: 0, upcoming: 0, total: 0 };
    }
}

/**
 * Load all events and populate the table
 * Called on page load for admin_event
 */
async function loadEvents() {
    const tableBody = document.getElementById('eventTableBody');
    
    if (!tableBody) {
        console.warn('Event table body not found');
        return;
    }

    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="7" class="p-8 text-center">
                <div class="flex flex-col items-center gap-3">
                    <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">loading</span>
                    <p class="font-medium text-slate-500">Loading events...</p>
                </div>
            </td>
        </tr>
    `;

    // Fetch events
    const events = await fetchEvents();

    if (events.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="p-8 text-center text-slate-500 dark:text-slate-400">
                    <div class="flex flex-col items-center gap-3">
                        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">event_note</span>
                        <p class="font-medium">No events found</p>
                        <p class="text-sm">Create your first event to get started.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    // Build HTML for events table
    let html = '';
    for (const event of events) {
        const eventDate = new Date(event.eventDate || event.start_date);
        const formattedDate = eventDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        const formattedTime = eventDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        
        const societyName = event.society ? event.society.name : 'N/A';
        const attendeesCount = event.ticketOrders ? event.ticketOrders.length : 0;
        
        // Determine status
        let status = 'Pending';
        let statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        
        if (event.isApproved === true || event.status === 'approved') {
            status = 'Approved';
            statusClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400';
        } else if (event.rejectionReason || event.status === 'rejected') {
            status = 'Rejected';
            statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        }

        html += `
            <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors">
                <td class="px-6 py-4">
                    <input class="h-4 w-4 rounded border-[#dbdfe6] text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700" type="checkbox" />
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium text-[#111318] dark:text-white">${event.name || 'Untitled Event'}</div>
                    <div class="text-xs text-slate-500">${event.location || 'No location specified'}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm">${societyName}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-[#111318] dark:text-white">${formattedDate}</div>
                    <div class="text-xs text-slate-500">${formattedTime}</div>
                </td>
                <td class="px-6 py-4">${attendeesCount}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ${statusClass}">
                        ${status}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        ${status === 'Pending' ? `
                            <button class="rounded px-2 py-1 text-xs font-medium text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/20 transition-colors" onclick="approveEvent(${event.id || event.eventID})">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            </button>
                            <button class="rounded px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors" onclick="rejectEvent(${event.id || event.eventID})">
                                <span class="material-symbols-outlined text-[16px]">cancel</span>
                            </button>
                        ` : ''}
                        <button class="text-slate-400 hover:text-primary transition-colors p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    tableBody.innerHTML = html;
    
    // Update event statistics
    const stats = await fetchEventStats();
    const pendingElement = document.getElementById('eventPendingCount');
    const upcomingElement = document.getElementById('eventUpcomingCount');
    const totalSocietiesElement = document.getElementById('eventTotalSocieties');
    const rejectedElement = document.getElementById('eventRejectedCount');
    
    if (pendingElement) pendingElement.textContent = stats.pendingApproval || 0;
    if (upcomingElement) upcomingElement.textContent = stats.upcoming || 0;
    if (totalSocietiesElement) totalSocietiesElement.textContent = stats.total || 0;
    if (rejectedElement) rejectedElement.textContent = stats.rejected || 0;
}

/**
 * Approve an event
 * @param {number} eventID 
 */
async function approveEvent(eventID) {
    if (!confirm('Are you sure you want to approve this event?')) return;
    
    try {
        const response = await fetch(`/api/events/${eventID}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        if (response.ok) {
            alert('Event approved successfully');
            loadEvents();
        } else {
            alert('Failed to approve event');
        }
    } catch (error) {
        console.error('Error approving event:', error);
        alert('Error approving event');
    }
}

/**
 * Reject an event
 * @param {number} eventID 
 */
async function rejectEvent(eventID) {
    const reason = prompt('Enter rejection reason:');
    if (!reason) return;
    
    try {
        const response = await fetch(`/api/events/${eventID}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ reason })
        });

        if (response.ok) {
            alert('Event rejected successfully');
            loadEvents();
        } else {
            alert('Failed to reject event');
        }
    } catch (error) {
        console.error('Error rejecting event:', error);
        alert('Error rejecting event');
    }
}

// Make functions globally available
window.fetchEvents = fetchEvents;
window.fetchEventStats = fetchEventStats;
window.loadEvents = loadEvents;
window.approveEvent = approveEvent;
window.rejectEvent = rejectEvent;

/**
 * Fetch active societies count (isDelete = 0)
 */
async function fetchActiveSocietiesCount() {
    try {
        const response = await fetch('/api/societies', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        const societies = data.societies || [];
        
        // Count societies where isDelete = 0 or isDelete is falsy
        const activeSocieties = societies.filter(society => !society.isDelete);
        
        return activeSocieties.length;
    } catch (error) {
        console.error('Error fetching active societies count:', error);
        return 0;
    }
}

/**
 * Update active societies count on dashboard
 */
async function updateActiveSocietiesCount() {
    const countElement = document.querySelector('[data-active-societies]');
    if (!countElement) {
        console.warn('Active societies count element not found');
        return;
    }

    const count = await fetchActiveSocietiesCount();
    countElement.textContent = count;
}

window.fetchActiveSocietiesCount = fetchActiveSocietiesCount;
window.updateActiveSocietiesCount = updateActiveSocietiesCount;

/**
 * Fetch active events count (is_deleted = 0)
 */
async function fetchActiveEventsCount() {
    try {
        const response = await fetch('/api/events', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        const events = data.events || [];
        
        // Count events where is_deleted = 0 or is_deleted is falsy
        const activeEvents = events.filter(event => !event.is_deleted);
        
        return activeEvents.length;
    } catch (error) {
        console.error('Error fetching active events count:', error);
        return 0;
    }
}

/**
 * Update active events count on dashboard
 */
async function updateActiveEventsCount() {
    const countElement = document.querySelector('[data-active-events]');
    if (!countElement) {
        console.warn('Active events count element not found');
        return;
    }

    const count = await fetchActiveEventsCount();
    countElement.textContent = count;
}

window.fetchActiveEventsCount = fetchActiveEventsCount;
window.updateActiveEventsCount = updateActiveEventsCount;

/**
 * Fetch upcoming events count (start_date > now)
 */
async function fetchUpcomingEventsCount() {
    try {
        const response = await fetch('/api/events', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        const events = data.events || [];
        const now = new Date();
        
        // Filter events where start_date is in the future
        const upcomingEvents = events.filter(event => {
            if (!event.start_date) return false;
            const eventDate = new Date(event.start_date);
            return eventDate > now;
        });
        
        return upcomingEvents.length;
    } catch (error) {
        console.error('Error fetching upcoming events count:', error);
        return 0;
    }
}

/**
 * Update upcoming events count on dashboard
 */
async function updateUpcomingEventsCount() {
    const countElement = document.querySelector('[data-upcoming-events]');
    if (!countElement) {
        console.warn('Upcoming events count element not found');
        return;
    }

    const count = await fetchUpcomingEventsCount();
    countElement.textContent = count;
}

window.fetchUpcomingEventsCount = fetchUpcomingEventsCount;
window.updateUpcomingEventsCount = updateUpcomingEventsCount;

/**
 * Fetch top 5 societies by member count
 */
async function fetchTopSocietiesByMembers() {
    try {
        const response = await fetch('/api/societies', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        console.log('Societies API Response:', data);
        
        let societies = data.societies || [];
        console.log('Societies before filter:', societies);
        
        // Filter active societies (isDelete = 0 or false) and sort by member count descending
        societies = societies
            .filter(society => !society.isDelete)
            .map(society => ({
                ...society,
                memberCount: society.memberCount || 0
            }))
            .sort((a, b) => b.memberCount - a.memberCount)
            .slice(0, 5);
        
        console.log('Top 5 societies:', societies);
        return societies;
    } catch (error) {
        console.error('Error fetching top societies:', error);
        return [];
    }
}

/**
 * Load and display top 5 societies
 */
async function loadTopSocieties() {
    const container = document.getElementById('topSocietiesContainer');
    
    if (!container) {
        console.warn('Top societies container not found');
        return;
    }

    const societies = await fetchTopSocietiesByMembers();
    console.log('Loading top societies, count:', societies.length);

    if (societies.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <p class="text-slate-500 dark:text-slate-400">No societies found</p>
            </div>
        `;
        return;
    }

    let html = '';
    societies.forEach((society, index) => {
        const memberCount = society.memberCount || 0;
        const maxMembers = societies[0].memberCount || 100;
        const percentage = (memberCount / maxMembers) * 100;
        
        html += `
            <div class="relative group">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center size-6 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold">${index + 1}</span>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-primary transition-colors">${society.name}</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase tracking-wide">Est. ${new Date(society.created_at || Date.now()).getFullYear()}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-slate-900 dark:text-white">${memberCount} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">Members</span></div>
                        <div class="flex items-center justify-end gap-1 text-xs text-green-600 font-medium">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            <span>Active</span>
                        </div>
                    </div>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-primary h-2 rounded-full transition-all duration-1000 ease-out" style="width: ${percentage}%"></div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

window.fetchTopSocietiesByMembers = fetchTopSocietiesByMembers;
window.loadTopSocieties = loadTopSocieties;

/**
 * Fetch total revenue from paid ticket orders
 */
async function fetchTotalRevenue() {
    try {
        const response = await fetch('/api/ticket-orders/revenue/total', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            console.error('Failed to fetch total revenue:', response.status);
            return 0;
        }

        const data = await response.json();
        return data.total_revenue || 0;
    } catch (error) {
        console.error('Error fetching total revenue:', error);
        return 0;
    }
}

/**
 * Update total revenue display on dashboard
 */
async function updateTotalRevenue() {
    const revenueElement = document.querySelector('[data-total-revenue]');
    if (!revenueElement) {
        console.warn('Revenue display element not found');
        return;
    }

    const revenue = await fetchTotalRevenue();
    revenueElement.textContent = 'RM ' + parseFloat(revenue).toFixed(2);
}

window.fetchTotalRevenue = fetchTotalRevenue;
window.updateTotalRevenue = updateTotalRevenue;

/**
 * Handle Add New Admin User form submission
 */
function handleAddUserForm() {
    const form = document.getElementById('addUserForm');
    
    if (!form) {
        return; // Form doesn't exist on this page
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('/user/create-admin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (response.ok) {
                alert('Admin user created successfully! A verification email has been sent to ' + data.email);
                document.getElementById('addUserModal').classList.add('hidden');
                location.reload();
            } else {
                alert('Error: ' + (result.message || 'Failed to create user'));
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });
}

window.handleAddUserForm = handleAddUserForm;

// Load societies when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    loadSocieties();
    loadEvents();
    updateActiveSocietiesCount();
    updateActiveEventsCount();
    updateUpcomingEventsCount();
    loadTopSocieties();
    updateTotalRevenue();
    handleAddUserForm();
});
