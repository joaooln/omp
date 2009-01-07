<?php
/**
 * @file SubmissionFormSequence.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SubmissionFormSequence
 * @ingroup submission
 *
 * @brief Represents a group of forms. Subclasses would be able to examine and react to state before displaying the sequence. Forms in groups should inherit from SequenceForm. 
 */

// $Id: 

class SubmissionFormSequence
{
	var $stepForms;
	var $monograph;
	var $currentStep;
	var $currentStepAlias;
	var $aliasLookup;

	function getNextStep() {
		return $this->currentStep+1;
	}
	function SubmissionFormSequence($monographId = null) {
		if (isset($monographId)) {
			$monographDao =& DAORegistry::getDAO('MonographDAO');
			$this->monograph =& $monographDao->getMonograph((int) $monographId);
		} else {
			$this->monograph = null;
		}
		$this->stepForms = array();
		$this->currentStep = 0;
		$this->currentStepAlias = null;
	}
	function display() {

		$templateMgr =& TemplateManager::getManager();
		$templateMgr->assign('submitStep', $this->currentStep);
		$templateMgr->assign('submitStepAlias', $this->currentStepAlias);
		$templateMgr->assign_by_ref('steplist', $this->stepForms);
		if(isset($this->monograph))
			$templateMgr->assign('monographId', $this->monograph->getMonographId());

	}
	function addForm($fullImportPath, $class, $guideTag, $title, $alias) {
		$step = count($this->stepForms)+1;
		$this->aliasLookup[$alias] = $step;

		$this->stepForms[$step] = array(
					'path'  => $fullImportPath, 
					'class' => $class, 
					'tag'   => $guideTag, 
					'title' => $title, 
					'alias' => $alias
					);
	}

	function &getFormForStep($stepAlias) {
		$step = isset($this->aliasLookup[$stepAlias]) ? $this->aliasLookup[$stepAlias] : 0;
		$this->validate($step);
		$this->currentStepAlias = $stepAlias;
		$this->currentStep = $step;

		import($this->stepForms[$step]['path']);
		$submitForm =& new $this->stepForms[$step]['class'];
		$submitForm->registerFormWithSequence($this);
		$submitForm->initializeInserts();

		return $submitForm;
	}

	function isValidStep($stepIndex, $isAliasIndex = false) {

		$press =& Request::getPress();

		if (!($stepIndex>0 && $stepIndex<=count($this->stepForms)) || (!isset($this->monograph) && $stepIndex != 1))
			return false;

		if (isset($this->monograph)) {
			if ($this->monograph->getPressId() !== $press->getPressId())
				return false;
		}

		return true;
	}

	function validate(){}
}
?>