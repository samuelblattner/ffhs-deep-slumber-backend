<?php
/**
 * Created by PhpStorm.
 * User: samuelblattner
 * Date: 18.08.18
 * Time: 16:46
 */

include __DIR__ . '/enums.php';

// Statics
const NAME_BT_DIGIT    = 'bt-digit';
const NAME_BT_OP       = 'bt-op';
const NAME_FIELD_VALUE = 'field-value';
const NAME_CALC_STATE  = 'calc-state';

// Globals
$valueFieldValue = 0;
$pendingFunction = null;
$calc            = null;

class Calculator {

	private $operandStack = [];
	private $opPtr = 0;
	private $pendingOperation = null;

	/**
	 * Calculator constructor.
	 *
	 */
	public function __construct( $serializedState ) {
		$this->operandStack = explode( ',', $serializedState );
		$this->__updateOpPtr();
		if ( $this->__hasPendingOperation() ) {
			array_push( $this->operandStack, 0 );
			$this->__updateOpPtr();
		}
	}

	private function __updateOpPtr() {
		$this->opPtr = sizeof( $this->operandStack ) - 1;
	}

	private function __hasPendingOperation() {
		if ( sizeof( $this->operandStack ) > 0 ) {
			$fn = new MathFunction( (string)end( $this->operandStack ) );
			if ( $fn != null && $fn != MathFunction::NOOP ) {
				return true;
			}
		}

		return false;
	}

	private function reducePendingOperations() {

//		while ( sizeof( $this->operandStack ) >= 3 ) {

			$resultStack = array();
			$op1         = null;
			$op2         = null;
			$operator    = null;

			foreach ( $this->operandStack as $op ) {

				$triedOperator = new MathFunction( (string)$op );

				if ( $triedOperator == MathFunction::NOOP ) {
					if ( $op1 == null ) {
						$op1 = $op;
					} else if ( $op2 == null ) {
						$op2 = $op;
					}
				} else {
				    $operator = $triedOperator;
                }

				if ( $operator != null && $operator != MathFunction::NOOP && $op1 != null && $op2 != null ) {
					switch ( $operator ) {
						case MathFunction::ADDITION: {
							array_push( $resultStack, $op1 + $op2 );
							break;
						}
						case MathFunction::SUBTRACTION: {
							array_push( $resultStack, $op1 - $op2 );
							break;
						}
						case MathFunction::MULTIPLICATION: {
							array_push( $resultStack, $op1 * $op2 );
							break;
						}
						case MathFunction::DIVISION: {
							array_push( $resultStack, $op1 / $op2 );
							break;
						}
						case MathFunction::MODULO: {
							array_push( $resultStack, $op1 % $op2 );
						}

					}

					$this->operandStack = $resultStack;
					$this->__updateOpPtr();
					echo $this->opPtr;


				}
			}

//		}
	}

	function handleDigitInput( $digitVal ) {

		if ( $this->__hasPendingOperation() ) {
			array_push( $this->operandStack, 0 );
			$this->__updateOpPtr();
		}

		if ( $this->operandStack[ $this->opPtr ] == 0 ) {
			$this->operandStack[ $this->opPtr ] = $digitVal;
		} else {
			$this->operandStack[ $this->opPtr ] .= $digitVal;
		}
	}

	function handleOperation( $opName ) {
		$this->reducePendingOperations();
		if (!$this->__hasPendingOperation()) {
			array_push( $this->operandStack, new MathFunction( $opName ) );
		}
	}

	public function getCurrentOperand() {
		return $this->operandStack[ $this->opPtr ];
	}

	public function serializeState() {
		return join( ',', $this->operandStack );
	}

	public function render() {
		$rendered = '<table>';

		// Build display
		// -------------
		$rendered .= '<tr><td colspan="2">'
		             . '<input name="' . NAME_FIELD_VALUE . '" type="number" value="' . $this->getCurrentOperand() . '" style="text-align: right;"/>'
		             . '<input name="' . NAME_CALC_STATE . '"  type="hidden" value="' . $this->serializeState() . '"/>'
		             . '</td></tr>';

		// Build number keypad
		// -------------------
		$rendered .= '<tr><td><table><tr>';
		for ( $d = 0; $d < 10; $d ++ ) {
			$rendered .= '<td><button name="' . NAME_BT_DIGIT . '" value="' . $d . '">' . $d . '</button></td>';
			if ( $d % 3 == 0 ) {
				$rendered .= '</tr><tr>';
			}
		}
		$rendered .= '</tr></table></td>';

		// Build operations keypad
		// -----------------------
		$rendered .= '<td><table>';
		$rendered .= '<tr><td><button name="' . NAME_BT_OP . '" value="add">+</button></td></tr>';
		$rendered .= '<tr><td><button name="' . NAME_BT_OP . '" value="sub">-</button></td></tr>';
		$rendered .= '<tr><td><button name="' . NAME_BT_OP . '" value="mul">*</button></td></tr>';
		$rendered .= '<tr><td><button name="' . NAME_BT_OP . '" value="div">/</button></td></tr>';
		$rendered .= '<tr><td><button name="' . NAME_BT_OP . '" value="mod">%</button></td></tr>';
		$rendered .= '</table></td>';


		$rendered .= '</tr></table>';

		return $rendered;
	}
}

/**
 *
 */
function handlePost() {

	global $calc;

	$calcState = null;
	$fieldVal  = null;
	$digitVal  = null;
	$fnName    = null;

	foreach ( $_POST as $argName => $argValue ) {
		switch ( $argName ) {
			case NAME_CALC_STATE: {
				$calcState = $argValue;
				break;
			}
			case NAME_FIELD_VALUE: {
				$fieldVal = $argValue;
				break;
			}
			case NAME_BT_DIGIT: {
				$digitVal = $argValue;
				break;
			}
			case NAME_BT_OP: {
				$fnName = $argValue;
			}
		}
	}

	$calc = new Calculator( $calcState );

	if ( $digitVal != null ) {
		$calc->handleDigitInput( $digitVal );
	} else if ( $fnName != null ) {
		$calc->handleOperation( $fnName );
	}
}

handlePost();

?>

<form method="POST" action="calculator.php">

	<?php echo $calc->render(); ?>

</form>
