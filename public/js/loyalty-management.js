/**
 * نظام نقاط الولاء - JavaScript للتفاعل
 */

// تهيئة النظام
document.addEventListener('DOMContentLoaded', function() {
    initializeLoyaltySystem();
});

/**
 * تهيئة نظام نقاط الولاء
 */
function initializeLoyaltySystem() {
    // تهيئة الأدوات التفاعلية
    initializeTooltips();
    initializeModals();
    initializeFilters();
    initializeAnimations();
    initializeAutoRefresh();

    // تهيئة الرسوم البيانية إذا كانت متوفرة
    if (typeof Chart !== 'undefined') {
        initializeCharts();
    }
}

/**
 * تهيئة التلميحات
 */
function initializeTooltips() {
    // تفعيل التلميحات من Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    // تلميحات مخصصة للنقاط
    $('.points-display').each(function() {
        $(this).attr('data-toggle', 'tooltip');
        $(this).attr('data-placement', 'top');
        $(this).attr('title', 'النقاط المتاحة للاستخدام');
    });

    // تلميحات للمعاملات
    $('.transaction-type').each(function() {
        const type = $(this).text().toLowerCase();
        let tooltip = '';

        switch(type) {
            case 'earned':
                tooltip = 'نقاط مكتسبة من الطلبات أو الإضافة اليدوية';
                break;
            case 'used':
                tooltip = 'نقاط مستخدمة في الطلبات';
                break;
            case 'expired':
                tooltip = 'نقاط منتهية الصلاحية';
                break;
            case 'refunded':
                tooltip = 'نقاط مستردة';
                break;
        }

        $(this).attr('data-toggle', 'tooltip');
        $(this).attr('data-placement', 'top');
        $(this).attr('title', tooltip);
    });
}

/**
 * تهيئة النوافذ المنبثقة
 */
function initializeModals() {
    // تهيئة modal إضافة النقاط
    $('#addPointsModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const userId = button.data('user-id');
        const userName = button.data('user-name');

        if (userId && userName) {
            $('#modal_user_id').val(userId);
            $('#modal_user_name').val(userName);
        }

        // مسح النموذج
        clearAddPointsForm();
    });

    // تهيئة modal حذف المعاملة
    $('.delete-transaction-btn').on('click', function(e) {
        e.preventDefault();
        const transactionId = $(this).data('transaction-id');
        const transactionDescription = $(this).data('transaction-description');

        if (confirm(`هل أنت متأكد من حذف المعاملة: ${transactionDescription}؟\nسيتم استرداد النقاط للمستخدم.`)) {
            deleteTransaction(transactionId);
        }
    });
}

/**
 * تهيئة الفلاتر
 */
function initializeFilters() {
    // فلتر البحث المباشر
    $('#search').on('input', debounce(function() {
        applyFilters();
    }, 300));

    // فلتر النوع
    $('#type').on('change', function() {
        applyFilters();
    });

    // فلتر المصدر
    $('#source').on('change', function() {
        applyFilters();
    });

    // فلتر التاريخ
    $('#date_from, #date_to').on('change', function() {
        applyFilters();
    });

    // فلتر النقاط
    $('#min_points, #max_points').on('input', debounce(function() {
        applyFilters();
    }, 300));
}

/**
 * تطبيق الفلاتر
 */
function applyFilters() {
    const form = $('form[action*="loyalty-management"]');
    if (form.length) {
        // إضافة مؤشر التحميل
        showLoadingIndicator();

        // إرسال النموذج
        form.submit();
    }
}

/**
 * تهيئة الرسوم المتحركة
 */
function initializeAnimations() {
    // تأثير الظهور التدريجي للبطاقات
    $('.loyalty-card, .stat-card, .loyalty-user-card, .transaction-card').each(function(index) {
        $(this).css('opacity', '0').css('transform', 'translateY(20px)');

        setTimeout(() => {
            $(this).addClass('fade-in');
        }, index * 100);
    });

    // تأثير التمرير للبطاقات
    $(window).on('scroll', function() {
        $('.stat-card, .loyalty-user-card, .transaction-card').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('slide-in-right');
            }
        });
    });
}

/**
 * تهيئة التحديث التلقائي
 */
function initializeAutoRefresh() {
    // تحديث الإحصائيات كل 30 ثانية
    setInterval(function() {
        refreshStats();
    }, 30000);

    // تحديث المعاملات كل دقيقة
    setInterval(function() {
        refreshTransactions();
    }, 60000);
}

/**
 * تحديث الإحصائيات
 */
function refreshStats() {
    // تحديث الإحصائيات في لوحة التحكم فقط
    if (window.location.pathname.includes('dashboard')) {
        $.ajax({
            url: '/dashboard/loyalty-management/stats',
            method: 'GET',
            success: function(data) {
                updateStatsDisplay(data);
            },
            error: function() {
                console.log('فشل في تحديث الإحصائيات');
            }
        });
    }
}

/**
 * تحديث المعاملات
 */
function refreshTransactions() {
    // تحديث المعاملات في صفحة المعاملات فقط
    if (window.location.pathname.includes('transactions')) {
        $.ajax({
            url: window.location.href,
            method: 'GET',
            success: function(data) {
                // تحديث الجدول فقط
                const newTable = $(data).find('.transaction-card').parent();
                $('.transaction-card').parent().html(newTable.html());

                // إعادة تهيئة الأدوات
                initializeTooltips();
                initializeModals();
            },
            error: function() {
                console.log('فشل في تحديث المعاملات');
            }
        });
    }
}

/**
 * تحديث عرض الإحصائيات
 */
function updateStatsDisplay(stats) {
    // تحديث الأرقام مع تأثير العد
    $('.stat-item .number').each(function() {
        const $this = $(this);
        const newValue = stats[$this.data('stat')];
        if (newValue !== undefined) {
            animateNumber($this, parseInt($this.text().replace(/,/g, '')), newValue);
        }
    });
}

/**
 * تأثير العد للأرقام
 */
function animateNumber($element, start, end) {
    const duration = 1000;
    const startTime = performance.now();

    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        const current = Math.round(start + (end - start) * progress);
        $element.text(current.toLocaleString());

        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        }
    }

    requestAnimationFrame(updateNumber);
}

/**
 * تهيئة الرسوم البيانية
 */
function initializeCharts() {
    // رسم بياني للإحصائيات الشهرية
    const monthlyStats = window.monthlyStats || [];
    if (monthlyStats.length > 0) {
        createMonthlyChart(monthlyStats);
    }

    // رسم بياني لتوزيع النقاط
    const pointsDistribution = window.pointsDistribution || {};
    if (Object.keys(pointsDistribution).length > 0) {
        createPointsDistributionChart(pointsDistribution);
    }
}

/**
 * إنشاء رسم بياني للإحصائيات الشهرية
 */
function createMonthlyChart(data) {
    const ctx = document.getElementById('monthlyChart');
    if (!ctx) return;

    const labels = data.map(item => item.month);
    const earnedData = data.map(item => item.points_earned);
    const usedData = data.map(item => item.points_used);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'النقاط المكتسبة',
                data: earnedData,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'النقاط المستخدمة',
                data: usedData,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

/**
 * إنشاء رسم بياني لتوزيع النقاط
 */
function createPointsDistributionChart(data) {
    const ctx = document.getElementById('pointsDistributionChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['النقاط المتاحة', 'النقاط المستخدمة', 'النقاط المنتهية'],
            datasets: [{
                data: [data.available, data.used, data.expired],
                backgroundColor: [
                    '#28a745',
                    '#dc3545',
                    '#ffc107'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

/**
 * إضافة نقاط للمستخدم
 */
function addPointsToUser(userId, userName) {
    $('#modal_user_id').val(userId);
    $('#modal_user_name').val(userName);
    clearAddPointsForm();
    $('#addPointsModal').modal('show');
}

/**
 * مسح نموذج إضافة النقاط
 */
function clearAddPointsForm() {
    $('#modal_points').val('');
    $('#modal_platform_contribution').val('');
    $('#modal_customer_contribution').val('');
    $('#modal_description').val('');
}

/**
 * حذف معاملة
 */
function deleteTransaction(transactionId) {
    showLoadingIndicator();

    $.ajax({
        url: `/dashboard/loyalty-management/transactions/${transactionId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoadingIndicator();
            showSuccessMessage('تم حذف المعاملة واسترداد النقاط بنجاح');

            // إعادة تحميل الصفحة
            setTimeout(() => {
                location.reload();
            }, 1500);
        },
        error: function(xhr) {
            hideLoadingIndicator();
            showErrorMessage('حدث خطأ في حذف المعاملة');
        }
    });
}

/**
 * تصدير التقرير
 */
function exportReport() {
    showLoadingIndicator();

    const form = $('form[action*="loyalty-management"]');
    const formData = form.serialize();

    window.location.href = `/dashboard/loyalty-management/export?${formData}`;

    setTimeout(() => {
        hideLoadingIndicator();
    }, 2000);
}

/**
 * عرض مؤشر التحميل
 */
function showLoadingIndicator() {
    if (!$('#loadingIndicator').length) {
        $('body').append(`
            <div id="loadingIndicator" class="loading-indicator">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">جاري التحميل...</span>
                </div>
                <p>جاري التحميل...</p>
            </div>
        `);
    }
    $('#loadingIndicator').show();
}

/**
 * إخفاء مؤشر التحميل
 */
function hideLoadingIndicator() {
    $('#loadingIndicator').hide();
}

/**
 * عرض رسالة نجاح
 */
function showSuccessMessage(message) {
    showAlert(message, 'success');
}

/**
 * عرض رسالة خطأ
 */
function showErrorMessage(message) {
    showAlert(message, 'danger');
}

/**
 * عرض تنبيه
 */
function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;

    $('.container-fluid').prepend(alertHtml);

    // إخفاء التنبيه تلقائياً بعد 5 ثوان
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

/**
 * دالة تأخير للبحث
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * تنسيق الأرقام
 */
function formatNumber(num) {
    return num.toLocaleString('ar-SA');
}

/**
 * تنسيق العملة
 */
function formatCurrency(amount) {
    return `${formatNumber(amount)} ريال`;
}

/**
 * تنسيق التاريخ
 */
function formatDate(date) {
    return new Date(date).toLocaleDateString('ar-SA');
}

/**
 * تنسيق الوقت النسبي
 */
function formatRelativeTime(date) {
    const now = new Date();
    const diff = now - new Date(date);
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 60) {
        return `منذ ${minutes} دقيقة`;
    } else if (hours < 24) {
        return `منذ ${hours} ساعة`;
    } else {
        return `منذ ${days} يوم`;
    }
}

// CSS إضافي للتحميل
const additionalCSS = `
    .loading-indicator {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-indicator .spinner-border {
        width: 3rem;
        height: 3rem;
        margin-bottom: 1rem;
    }

    .loading-indicator p {
        color: #667eea;
        font-weight: 500;
    }

    .fade-in {
        opacity: 1 !important;
        transform: translateY(0) !important;
        transition: all 0.5s ease;
    }

    .slide-in-right {
        opacity: 1 !important;
        transform: translateX(0) !important;
        transition: all 0.5s ease;
    }
`;

// إضافة CSS إلى الصفحة
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);
