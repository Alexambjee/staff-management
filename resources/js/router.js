import Vue from 'vue'
import Router from 'vue-router';
Vue.use(Router);
// project pages
import login from './components/pages/login'
// =================== super_admin pages=========================
import superAdmin from './roles/super-admin/super_admin'
// =================== admin pages=========================
import admin from './roles/admin/admin'
import adminrolemanagment from './roles/admin/pages/role-management'
import adminusermapping from './roles/admin/pages/user-mapping'
import adminusermanagment from './roles/admin/pages/user-management'
import admininbox from './roles/admin/pages/inbox'
import adminprogressive from './roles/admin/pages/admin-progress'
import admincasemanagment from './roles/admin/pages/case-management'
import adminassign from './roles/admin/pages/Assign'
import adManualCases from './roles/admin/pages/manual-cases'
import adminprogress from './roles/admin/pages/casepro'
import adminclosed from './roles/admin/pages/closed'
import AdminProgressDetails from './roles/admin/pages/progress-details'
// ===================  team_lead pages=========================
import teamLead from './roles/team-lead/team_lead'
import TeamLeadCases from './roles/team-lead/pages/mycases'
import TeamLeadCaseDetails from './roles/team-lead/pages/case-details'
import TeamLeadCaseDetailsM from './roles/team-lead/pages/manualdetails'

import TeamLeadSentBack from './roles/team-lead/pages/sent-back'
import TeamLeadSentBackDetails from './roles/team-lead/pages/sent-back-details'
import TeamLeadManualCases from './roles/team-lead/pages/manual-cases'
import Tlinbox from './roles/team-lead/pages/inbox'
import TeamLeadPrelim from './roles/team-lead/pages/prelim-report'
import TeamLeadPrelimDetails from './roles/team-lead/pages/prelim-report-details'
import TeamLeadFindings from './roles/team-lead/pages/report-findings'
import TeamLeadFindingsDetails from './roles/team-lead/pages/report-findings-details'
import TmProgressDetails from './roles/team-lead/pages/progress-details'
// =================== tso_head pages=========================

import tsoHead from './roles/tso-head/tso_head'
import tsoHeadCases from './roles/tso-head/pages/mycases'
import tsoHeadassign from './roles/tso-head/pages/assign'
import Tsoinbox from './roles/tso-head/pages/inbox'
import tsoHeadCaseDetails from './roles/tso-head/pages/case-details'
import tsoHeadCaseDetailsM from './roles/tso-head/pages/manualdetails'
import tsoHeadManualCases from './roles/tso-head/pages/manual-cases'
import tsoProgressDetailsd from './roles/tso-head/pages/progress-details'
// =================== sector_manager pages=========================
import sectorManager from './roles/sector-manager/sector_manager'
import sectorMgrCases from './roles/sector-manager/pages/mycases'
import sectorMgrCaseDetails from './roles/sector-manager/pages/case-details'
import sectorMgrCaseDetailsM from './roles/sector-manager/pages/manualdetails'
import sectorMgrManualCases from './roles/sector-manager/pages/manual-cases'
import SMinbox from './roles/sector-manager/pages/inbox'
import sectorMgrSentBack from './roles/sector-manager/pages/sent-back'
import sectorMgrSentBackDetails from './roles/sector-manager/pages/sent-back-details'
import sectorMgrPrelim from './roles/sector-manager/pages/prelim-report'
import sectorMgrPrelimDetails from './roles/sector-manager/pages/prelim-report-details'
import sectorMgrFindings from './roles/sector-manager/pages/report-findings'
import sectorMgrFindingsDetails from './roles/sector-manager/pages/report-findings-details'
import scProgressDetails from './roles/sector-manager/pages/progress-details'

// =================== account_manager pages=========================
import accountManager from './roles/account-manager/account_manager'
import AMinbox from './roles/account-manager/pages/inbox'
import accMgtCases from './roles/account-manager/pages/mycases'
import accMgtManualCases from './roles/account-manager/pages/manual-cases'
import accMgtCaseDetails from './roles/account-manager/pages/case-details'
import accMgtSentback from './roles/account-manager/pages/sent-back'
import accMgtSentbackDetails from './roles/account-manager/pages/sent-back-details'
import accMgtTaxpayerContact from './roles/account-manager/pages/contact-taxpayer'
import accMgtRoleChange from './roles/account-manager/pages/role-change'
import accMgtCasesProgress from './roles/account-manager/pages/cases-progress'
import accMgtProgressDetails from './roles/account-manager/pages/progress-details'
import accMgtClosedCases from './roles/account-manager/pages/closed-cases'
// import accMgtClosedDetails from './roles/account-manager/pages/closed-cases'
import accMgtReturns from './roles/account-manager/pages/assessment-returns'
import accMgtReturnsDetails from './roles/account-manager/pages/assessment-returns-details'
import accMgtTaxpayerResponse from './roles/account-manager/pages/taxpayer-response'
import accMgtaxpayerResponseDetails from './roles/account-manager/pages/taxpayer-response-details'


// ============================= REPORT ========================
import tlProgressDetails from './roles/team-lead/pages/cases-progress'
import tlClosedCases from './roles/team-lead/pages/closed-cases'
import smProgressDetails from './roles/sector-manager/pages/cases-progress'
import smClosedCases from './roles/sector-manager/pages/closed-cases'
import tsoProgressDetails from './roles/tso-head/pages/cases-progress'
import tsoClosedCases from './roles/tso-head/pages/closed-cases'

import Risk from './roles/Risk/index'
import Riskselection from './roles/Risk/selection'
import Riskfound from './roles/Risk/riskTable'

const routes = [

    //risk
    {
        path: '/risk',
        component: Risk
    },
    {
        path: '/risk/risk_selection',
        component: Riskselection
    },
    {
        path: '/risk/risk_table',
        component: Riskfound
    },

    {
        path: '/',
        component: login,
        name: 'login',
        meta: 'Login'

    },
    //  =================== Super admin routes================
    {
        path: '/Super admin',
        component: superAdmin
    },
    //  =================== admin routes================
    {
        path: '/admin',
        component: admin

    },
    {
        path: '/admin/mycases',
        component: admincasemanagment

    },
    {
        path: '/admin/Manual-cases',
        component: adManualCases
    },
    {
        path: '/admin/role-management',
        component: adminrolemanagment

    },
    {
        path: '/admin/user-managment',
        component: adminusermanagment

    },
    {
        path: '/admin/user-mapping',
        component: adminusermapping

    },
    {
        path: '/admin/inbox',
        component: admininbox

    },
    {
        path: '/admin/progressive',
        component: adminprogressive

    },
    {
        path: '/admin/assign',
        component: adminassign

    },
    {
        path: '/admin/cases-progress',
        component: adminprogress

    },
    {
        path: '/admin/closed-cases',
        component: adminclosed

    },
    {
        path: '/Admin/cases-progress/:caseId',
        component:AdminProgressDetails,
    },
    {
        path: '/Admin/cases-progress/:caseId',
        component:AdminProgressDetails,
    },
   
    //  =================== tso_head routes================
    {
        path: '/Tso-head',
        component: tsoHead
    },
    {
        path: '/Tso-head/messages',
        component: Tsoinbox
    },
    
    {
        path: '/Tso-head/reassign-cases',
        component: tsoHeadassign
    },
    
    {
        path: '/Tso-head/my-cases',
        component: tsoHeadCases
    },
    {
        path: '/Tso-head/my-cases/:caseId',
        component: tsoHeadCaseDetails
    },
    {
        path: '/Tso-head/Manual-cases/:caseId',
        component: tsoHeadCaseDetailsM
    },
    {
        path: '/Tso-head/Manual-cases',
        component: tsoHeadManualCases
    },
    {
        path: '/Tso-head/closed-cases',
        component: tsoClosedCases
    },
    {
        path: '/Tso-head/cases-progress',
        component: tsoProgressDetails
    },
     {
        path: '/Tso-head/cases-progress/:caseId',
        component:tsoProgressDetailsd,
    },
   // closed-cases details
    {
        path: '/Tso-head/closed-cases/:caseId',
        component:tsoProgressDetailsd,
    },
   
    
    //  =================== sector_manager routes================
    // dashboard
    {
        path: '/Sector-manager',
        component: sectorManager
    },
        {
        path: '/Sector-manager/messages',
        component: SMinbox
    },
    // my cases
    {
        path: '/Sector-manager/my-cases',
        component: sectorMgrCases
    },
    // my cases details
    {
        path: '/Sector-manager/my-cases/:caseId',
        component: sectorMgrCaseDetails
    },
    {
        path: '/Sector-manager/Manual-cases/:caseId',
        component: sectorMgrCaseDetailsM
    },
    
    // sent back cases
    {
        path: '/Sector-manager/sent-back-cases',
        component: sectorMgrSentBack
    },
    // sent back cases details
    {
        path: '/Sector-manager/sent-back-cases/:caseId',
        component: sectorMgrSentBackDetails

    },
     // Report findings
     {
        path: '/Sector-manager/Report-findings',
        component: sectorMgrFindings,
    },
     // Report findings details
     {
        path: '/Sector-manager/Report-findings/:caseId',
        component: sectorMgrFindingsDetails,
    },
     // preliminary reports
     {
        path: '/Sector-manager/Preliminary-reports',
        component: sectorMgrPrelim,
    },
     // preliminary reports details
     {
        path: '/Sector-manager/Preliminary-reports/:caseId',
        component: sectorMgrPrelimDetails,
    },
    {
        path: '/Sector-manager/Manual-cases',
        component: sectorMgrManualCases,
    },
    {
        path: '/Sector-manager/closed-cases',
        component: smClosedCases,
    },
    {
        path: '/Sector-manager/cases-progress',
        component: smProgressDetails,
    },
    {
        path: '/Sector-manager/cases-progress/:caseId',
        component:scProgressDetails,
    },
   // closed-cases details
    {
        path: '/Sector-manager/closed-cases/:caseId',
        component:scProgressDetails,
    },

    //  =================== Team-lead routes================
    {
        path: '/Team-lead',
        component: teamLead,
    },
          {
        path: '/Team-lead/messages',
        component: Tlinbox
    },
    {
        path: '/Team-lead/my-cases',
        component: TeamLeadCases,
    },
    {
        path: '/Team-lead/my-cases/:caseId',
        component: TeamLeadCaseDetails,
    },
    {
        path: '/Team-lead/Manual-cases/:caseId',
        component: TeamLeadCaseDetailsM,
    },
    // sent back
    {
        path: '/Team-lead/sent-back-cases',
        component: TeamLeadSentBack,
    },
    // sentback details
    {
        path: '/Team-lead/sent-back-cases/:caseId',
        component: TeamLeadSentBackDetails,
    },
     // manual cases
     {
        path: '/Team-lead/Manual-cases',
        component: TeamLeadManualCases,
    },
     // Report findings
     {
        path: '/Team-lead/Report-findings',
        component: TeamLeadFindings,
    },
     // Report findings details
     {
        path: '/Team-lead/Report-findings/:caseId',
        component: TeamLeadFindingsDetails,
    },
     // preliminary reports
     {
        path: '/Team-lead/Preliminary-reports',
        component: TeamLeadPrelim,
    },
     // preliminary reports details
     {
        path: '/Team-lead/Preliminary-reports/:caseId',
        component: TeamLeadPrelimDetails,
    },
    {
        path: '/Team-lead/closed-cases',
        component: tlClosedCases,
    },
    {
        path: '/Team-lead/cases-progress',
        component: tlProgressDetails,
    },
        {
        path: '/Team-lead/cases-progress/:caseId',
        component:TmProgressDetails,
    },
   // closed-cases details
    {
        path: '/Team-lead/closed-cases/:caseId',
        component:TmProgressDetails,
    },
    //  =================== Account-manager routes================
    {
        path: '/Account-manager',
        component: accountManager,
    },
          {
        path: '/Account-manager/messages',
        component: AMinbox
    },
    {
        path: '/Account-manager/my-cases',
        component: accMgtCases,
    },
    {
        path: '/Account-manager/my-cases/:caseId',
        component: accMgtCaseDetails,
    },
    // sent back cases
    {
        path: '/Account-manager/sent-back-cases',
        component: accMgtSentback,
    },
    // sent back details
    {
        path: '/Account-manager/sent-back-cases/:caseId',
        component: accMgtSentbackDetails,
    },
    // manual cases
    {
        path: '/Account-manager/Manual-cases',
        component: accMgtManualCases,
    },
    // contact taxpayer
    {
        path: '/Account-manager/contact-taxpayer/:caseId',
        component: accMgtTaxpayerContact,
    },
    // temporay role change
    {
        path: '/Account-manager/Temporary-role-change',
        component: accMgtRoleChange,
    },
    // cases Progress
    {
        path: '/Account-manager/cases-progress',
        component: accMgtCasesProgress,
    },
    {
        path: '/Account-manager/cases-progress/:caseId',
        component: accMgtProgressDetails,
    },
    // closed cases
    {
        path: '/Account-manager/closed-cases',
        component: accMgtClosedCases,
    },
    // closed-cases details
    {
        path: '/Account-manager/closed-cases/:caseId',
        component:accMgtProgressDetails,
    },
    // assessment and returns
    {
        path: '/Account-manager/assessment-and-returns',
        component: accMgtReturns,
    },
    // assessment and returns details
    {
        path: '/Account-manager/assessment-and-returns/:caseId',
        component: accMgtReturnsDetails,
    },
    // response from taxpayer
    {
        path: '/Account-manager/taxpayer-response',
        component: accMgtTaxpayerResponse,
    },
    // response from taxpayer details
    {
        path: '/Account-manager/taxpayer-response/:caseId',
        component: accMgtaxpayerResponseDetails,
    },
    
    // ======================== REPORT ========================

]

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

