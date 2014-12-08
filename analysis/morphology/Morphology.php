<?php
/**
 * @file
 * @brief 解析関連
 * @author yano-tatsuya
 * @date 2014-05-20
 */

require_once ini_get('include_path') . '/analysis/Analysis.php';

class Morphology extends Analysis
{

	private $_sentence = null;
	private $_kind = array(
		'noun' => '名詞',
		'verb' => '動詞'
	);

	private $_word = array();

	public function countWord($kind = 'noun') {

		$mecab = new MeCab_Tagger();
		$preg = $this->_kind[$kind];

		for ($node = $mecab->parseToNode($this->_sentence); $node; $node=$node->getNext()) {

			if (preg_match("/$preg/", $node->getFeature())) {

				if (isset($this->_word[$node->getSurface()])) {
					$this->_word[$node->getSurface()]++;
				} else {
					$this->_word[$node->getSurface()] = 1;
				}

			}

		}

		return true;

	}

	public function setSentence($sentence) {
		$this->_sentence = $sentence;
	}

	public function getWord() {
		return $this->_word;
	}
}