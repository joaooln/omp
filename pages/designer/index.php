<?php

/**
 * @defgroup pages_designer
 */
 
/**
 * @file pages/designer/index.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup pages_designer
 * @brief Handle requests for designer functions. 
 *
 */

// $Id$

switch ($op) {
	case 'viewMetadata':
	//
	// Submission Layout Editing
	//
	case 'submission':
	case 'submissionEditing':
	case 'completeAssignment':
	case 'uploadLayoutFile':
	case 'editGalley':
	case 'saveGalley':
	case 'deleteGalley':
	case 'orderGalley':
	case 'proofGalley':
	case 'proofGalleyTop':
	case 'proofGalleyFile':
	case 'editSuppFile':
	case 'saveSuppFile':
	case 'deleteSuppFile':
	case 'orderSuppFile':
	case 'downloadFile':
	case 'viewFile':
	case 'downloadLayoutTemplate':
	case 'deleteMonographImage':
	//
	// Proofreading Actions
	//
	case 'designerProofreadingComplete':
		import('pages.designer.SubmissionLayoutHandler');
		define('HANDLER_CLASS', 'SubmissionLayoutHandler');
		break;
	//
	// Submission Comments
	//
	case 'viewLayoutComments':
	case 'postLayoutComment':
	case 'viewProofreadComments':
	case 'postProofreadComment':
	case 'editComment':
	case 'saveComment':
	case 'deleteComment':
		import('pages.designer.SubmissionCommentsHandler');
		define('HANDLER_CLASS', 'SubmissionCommentsHandler');
		break;
	default: 	
		define('HANDLER_CLASS', 'DesignerHandler');	
		import('pages.designer.DesignerHandler');
		break;
}

?>
