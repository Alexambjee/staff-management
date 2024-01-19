<?php

// use mail;
use App\Http\Controllers;
use App\Mail\emailTaxpayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IroUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TaxpayerController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\IroMappingController;
use App\Http\Controllers\NewPdfController;
use App\Http\Controllers\BlobController;
use App\Http\Controllers\AdminPdf;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

 Route::get('/getMessageUsers/{username}',[MessageController::class, 'getMessageUsers']);
// risk stuff
 Route::get('/fetchTables',[CaseController::class,'fetchTables']);
 Route::post('/submitRisk',[CaseController::class,'submitRisk']);
 Route::post('/create_risk_cases',[CaseController::class,'create_risk_cases']);

// ======================= ADMIN ROUTES ==========================
Route::get('/logout',[LoginController::class, 'Logout']);

//Getting taxpayer setails
Route::get('/getTaxpayerDetails/{tPin}',[TaxpayerController::class,'getTaxpayerDetails']); //oracle req
Route::get('/isPinExist/{tPin}',[TaxpayerController::class,'isPinExist']);//oracle req

Route::get('/getAuditTrail',[AuditTrailController::class,'getAuditTrail']);
Route::get('/getStationId/{tStation}',[TaxpayerController::class,'getStationId']);


//user routes
Route::get('/getAllUsers',[UserController::class,'getAllUsers']);
Route::get('/getAllUsersWithout',[UserController::class,'getAllUsersWithout']);
Route::get('/getUserInfoWhere/{userName}',[UserController::class,'getUserInfoWhere']);
Route::post('/createUser',[UserController::class,'createUser']);



Route::post('/uploadMapping',[IroMappingController::class,'uploadMapping']);

Route::post('/uploadManual',[CaseController::class,'uploadManual']);

Route::post('/uploadDwMapping',[IroMappingController::class,'uploadDwMapping']);
Route::get('/getUserMapping',[IroMappingController::class,'getUserMapping']);
Route::post('/activateDeactivateUser',[UserController::class,'activateDeactivateUser']);
Route::post('/changeUserRole',[UserController::class,'changeUserRole']);

// roles routes
Route::get('/fetchRoles',[RoleController::class,'fetchRoles']);
Route::get('/fetchRolesWithout',[RoleController::class,'fetchRolesWithout']);
Route::post('/storeRoles',[RoleController::class,'store']);
Route::post('/updateRole',[RoleController::class,'updateRole']);
Route::delete('/deleteRole',[RoleController::class,'deleteRole']);

// cases routes
Route::get('/getsmMAil/{smPin}',[CaseController::class,'smMAil']);
Route::get('/getassOff/{data}',[CaseController::class,'TsoassignCases']);
Route::get('/getAllCases',[CaseController::class,'getAllCases']);
Route::get('/getCases/{data}',[CaseController::class,'getCases']);
Route::get('/getReassignedCases',[CaseController::class,'getReassignedCases']);
Route::get('/fetchDetails/{caseId}',[CaseController::class,'fetchDetails']);
Route::get('/getTypeId/{caseId}',[CaseController::class,'fetchTypeID']);
Route::get('/getCaseCount/{data}',[CaseController::class,'getCaseCount']);
Route::get('/getCaseCountAdmin',[CaseController::class,'getCaseCountAdmin']);
Route::get('/getCaseCountAdmin/{data}', [CaseController::class, 'getCaseCountAdmin']);
// Route::get('/getTypeName/{typeId}',[CaseController::class,'getTypeName']);
// Route::get('/getTypeName/{typeId}',[CaseController::class,'fetchTypeName']);

Route::post('/caseForclosure',[CaseController::class,'closeCases']);

Route::get('/getRpHistory/{tpin}',[CaseController::class,'getRpHistory']);

// ferching taxpayer responses status
Route::get('/getResponsedCases/{data}',[CaseController::class,'fetchRespondedCases']);
Route::post('/createManualCase',[CaseController::class,'createManualCase']);
Route::post('/updateCase',[CaseController::class,'updateCase']);
//getting station
Route::get('/getStations',[CaseController::class,'getStations']);
//getting Obligation
Route::get('/getObligations',[CaseController::class,'getObligations']);

// officer total cases
Route::get('/TotalOfficerCases/{username}',[CaseController::class,'getOfficerTotalCases']);
Route::get('/ResponseTotalCases/{username}',[CaseController::class,'getOfficerTotalResponses']);
Route::get('/ForwardTotalCases/{username}',[CaseController::class,'getOfficerTotalForward']);
Route::get('/RejectedTotalCases/{username}',[CaseController::class,'getOfficerTotalRejected']);
// responses
Route::get('/getOfficerTotalResponsed/{username}',[CaseController::class,'getOfficerTotalResponsed']);
Route::get('/getOfficerTotalNotResponded/{username}',[CaseController::class,'getOfficerTotalNotResponded']);
Route::get('/getOfficerTotalAwaitingResponse/{username}',[CaseController::class,'getOfficerTotalAwaitingResponse']);

// chnging response status
Route::post('/changeResponseStatus',[CaseController::class,'changeResponseStatus']);
Route::post('/invalidateCase',[MessageController::class,'invalidateCase']);


//response form taxpayer route
Route::post('/submitReport',[CaseController::class,'submitReport']);
// case background
Route::post('/background',[CaseController::class,'background']);

//sent back cases route
Route::post('/ResubmitReport',[CaseController::class,'ResubmitReport']);

// =================attachment routes ==========
Route::get('/fetchAttachments/{caseId}',[AttachmentController::class,'fetchAttachments']);
// Route::get('/getAttachments/{tPin}',[AttachmentController::class,'getAttachment']);
Route::get('/getTaxpayerAttachments/{tPin}',[AttachmentController::class,'getTaxpayerAttachments']);
Route::get('/fetchAssessment/{caseId}',[CaseController::class,'fetchAssessment']);
Route::delete('/deleteAttachment/{caseId}/{attachmentId}', [AttachmentController::class,'deleteAttachment']);

// user routes
// Route::get('/getUsers',[UserController::class,'getUsers']);


// manager routes
Route::get('/TotalManagerCases/{username}',[CaseController::class,'getTotalManagerCases']);
Route::get('/ReassignTotalCases/{username}',[CaseController::class,'getManagerReassignCases']);
Route::get('/RejectedCases/{username}',[CaseController::class,'getManagerTotalRejected']);
Route::get('/ManagerTotalDecisions/{username}',[CaseController::class,'getManagerTotalDecisions']);


// chief manager routes
Route::get('/TotalChiefCases/{username}',[CaseController::class,'getTotalChiefCases']);
Route::get('/ChiefReassignTotalCases/{username}',[CaseController::class,'getChiefReassignCases']);
Route::get('/ChiefTotalInbox/{username}',[CaseController::class,'getChiefTotalInbox']);
Route::get('/ChiefTotalDecisions/{username}',[CaseController::class,'getChiefTotalDecisions']);

//Reassign cases routes
Route::get('/fetchOfficers/{Fdata}',[CaseController::class,'fetchOfficers']);
Route::post('/ReassignCase',[CaseController::class,'ReassignCase']);
Route::post('/AdminReassignCase',[CaseController::class,'AdminReassignCase']);


// approve/reject selection routes
Route::get('/fetchApprovals/{approvalType}',[CaseController::class,'fetchApprovals']);
Route::get('/getForwardOption/{approvalType}',[CaseController::class,'getForwardOption']);
Route::get('/fetchRejections/{approvalType}',[CaseController::class,'fetchRejections']);
Route::get('/getOptionName/{optionName}',[CaseController::class,'getOptionName']);
Route::get('/fetchReasons/{ReasonType}',[CaseController::class,'fetchReasons']);
// =================== approve, forward and reject case routes ====================
// approve case
Route::post('/ApproveCase',[CaseController::class,'ApproveCase']);
// reject case
Route::post('/RejectCase',[CaseController::class,'RejectCase']);
// forward to cm case
Route::post('/ForwardCaseToCM',[CaseController::class,'ForwardCaseToCM']);
// forwarding case decision
Route::post('/ForwardCaseDecision',[CaseController::class,'ForwardCaseDecision']);
Route::post('/ForwardCaseDecision',[CaseController::class,'ForwardCaseDecision']);

// ====================== INDEPENDENT ROUTES ==================
Route::post('/login',[LoginController::class,'Login']);

//messaging routes

// uploading files
Route::post('/upload',[AttachmentController::class,'upload']);
// fetching user messages
Route::get('/getMessages/{username}',[MessageController::class,'fetchMessages']);
// Route::get('/getUserTotalMessages/{username}',[MessageController::class,'getUserTotalMessages']);
//  fetching unread messages
Route::get('/unreadMessages/{username}',[MessageController::class,'unreadMessages']);
Route::post('/changeMessageStatus',[MessageController::class,'changeMessageStatus']);
// fetch select options based on type
Route::get('/fetchSelectOption/{type}',[CaseController::class,'fetchSelectOption']);
Route::get('/fetchDecisions/{decisiontype}',[CaseController::class,'fetchDecisions']);
// comments routes
Route::get('/get_comments/{caseId}',[CommentController::class,'getComments']);
// contacting taxpayer route
Route::post('/contactTaxpayer',[MessageController::class,'contactTaxpayer']);
// fetching taxpayer communication
Route::get('/getTaxpayerMessages/{caseId}',[MessageController::class,'fetchTaxpayerMmessages']);
//COMMENTS ROUTES
Route::get('/getComments',[CommentController::class,'getComments']);
// compose message route
Route::post('/composeMail',[MessageController::class,'composeMail']);
Route::post('/replyMail',[MessageController::class,'replyMail']);
// pdf routes
Route::get('/pdf/{caseId}', [PdfController::class, 'printPDF']);
Route::get('/pdfAdmin/{action}', [AdminPdf::class, 'printPDF']);

Route::get('/ResponseTotal/{username}',[CaseController::class,'getResponseTotal']);
Route::get('/AppealedTotal/{username}',[CaseController::class,'getAppealedTotal']);
Route::get('/CompletedTotal/{username}',[CaseController::class,'getCompletedTotal']);
Route::get('/ReassignedTotal/{username}',[CaseController::class,'getReassignedTotal']);
Route::get('/RejectedTotal/{username}',[CaseController::class,'getRejectedTotal']);

Route::get('/InProgress/{username}',[CaseController::class,'getInprogress']);
Route::get('/Assigned/{username}',[CaseController::class,'getAssigned']);
Route::get('/donData/{data}',[CaseController::class,'getDonData']);
Route::get('/barData/{data}',[CaseController::class,'getBarData']);

// ===================fetching reports++++==================
Route::get('/getProgress/{data}',[CaseController::class,'getProgress']);

// ===================fetching Admin reports++++==================
// Route::get('/getAdminProgress',[CaseController::class,'getAdminProgress']);
Route::get('/getAdminProgress',[CaseController::class,'getAdminProgress']);
Route::get('/fetchCompletedCases',[CaseController::class,'fetchCompletedCases']);
Route::get('/fetchInProgress',[CaseController::class,'fetchInProgress']);
Route::get('/revertedCases',[CaseController::class,'revertedCases']);
Route::get('/fetchReportData',[CaseController::class,'fetchReportData']);
Route::get('/getReportBarData',[CaseController::class,'getReportBarData']);

// pdf previews
Route::get('/pdf/contact-tp/{caseId}',[NewPdfController::class,'printContactTaxpayer']);

// Compares assement amounts
Route::get('/compare-assessment-amounts/{caseId}',[CaseController::class,'compareAsmntAmnts']);




// get reason for rejection
Route::get('/getReasonName/{reasonId}',[CaseController::class,'getReasonName']);

