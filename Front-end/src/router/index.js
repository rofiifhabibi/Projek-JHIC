import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  {
    path: '/',
    name: 'landing',
    component: () => import('@/views/public/LandingPage.vue'),
    meta: { layout: 'BlankLayout' }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/auth/LoginView.vue'),
    meta: { layout: 'BlankLayout', requiresGuest: true }
  },
  // 1. RUTE SISWA (PWA)
  {
    path: '/student',
    component: () => import('@/layouts/StudentLayout.vue'),
    redirect: { name: 'student-dashboard' },
    meta: { requiresAuth: true, roles: ['student'] },
    children: [
      { path: 'dashboard', name: 'student-dashboard', component: () => import('@/views/student/DashboardView.vue') },
      { path: 'permit/create', name: 'student-permit-create', component: () => import('@/views/student/PermitFormView.vue') },
      { path: 'permit/pass', name: 'student-permit-pass', component: () => import('@/views/student/QrPassView.vue') },
      { path: 'report/create', name: 'student-report-create', component: () => import('@/views/student/CareReportView.vue') },
      { path: 'tracking', name: 'student-tracking', component: () => import('@/views/student/TrackingView.vue') }
    ]
  },
  // 2. RUTE DASBOR GURU
  {
    path: '/teacher',
    component: () => import('@/layouts/TeacherLayout.vue'),
    redirect: { name: 'teacher-monitoring' },
    meta: { requiresAuth: true, roles: ['teacher'] },
    children: [
      { path: 'monitoring', name: 'teacher-monitoring', component: () => import('@/views/teacher/DynamicMonitoringView.vue') },
      { path: 'approvals', name: 'teacher-approvals', component: () => import('@/views/teacher/ApprovalQueueView.vue') }
    ]
  },
  // 3. RUTE SATPAM
  {
    path: '/satpam',
    component: () => import('@/layouts/SatpamLayout.vue'),
    redirect: { name: 'satpam-scanner' },
    meta: { requiresAuth: true, roles: ['satpam'] },
    children: [
      { path: 'scanner', name: 'satpam-scanner', component: () => import('@/views/satpam/WebScannerView.vue') }
    ]
  },
  // 4. RUTE PUSAT DATA BK
  {
    path: '/bk',
    component: () => import('@/layouts/BkLayout.vue'),
    redirect: { name: 'bk-kanban' },
    meta: { requiresAuth: true, roles: ['bk'] },
    children: [
      { path: 'kanban', name: 'bk-kanban', component: () => import('@/views/bk/KanbanView.vue') },
      { path: 'global-monitor', name: 'bk-global-monitor', component: () => import('@/views/bk/GlobalMonitorView.vue') }
    ]
  },
  // 5. RUTE 404 CATCH-ALL
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/views/public/NotFoundView.vue'),
    meta: { layout: 'BlankLayout' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation Guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next({ name: 'login' })
  }
  
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    return next(authStore.defaultRedirectRoute)
  }
  
  if (to.meta.roles && !to.meta.roles.includes(authStore.userRole)) {
    const dest = authStore.defaultRedirectRoute
    if (to.name !== dest.name) {
      return next(dest)
    }
    return next({ name: 'login' })
  }

  next()
})

export default router
