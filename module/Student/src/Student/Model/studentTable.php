<?php
/*
 *@author : Divesh Pahuja
 *@date : 10-07-2014  
 *
 */
namespace Student\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\Pdo\Pdo;
use Zend\Db\Adapter\Adapter;
use Zend\Session\Container;


class StudentTable 
{
	protected $dbAdapter;
	protected $tablegateway;
	protected $clientId;
	protected $code;
	protected $linkId;

	
	public function __construct()  
	{
		$quizSession = new Container('quiz');
		
		$this->clientId	=	$quizSession->clientId;
		$this->linkId	=	$quizSession->linkId;
		$this->code		=	$quizSession->code;
		
		$dbAdapterConfig = array(
				'driver'         =>  'Pdo',
				'dsn'            =>  'mysql:dbname=clientdb0'.$this->clientId.';host=localhost',
				'username' => 'root', //'client0'.$this->clientId,
				'password' =>'root' , //'client0'.$this->clientId,
		);
		
		$this->dbAdapter = new Adapter($dbAdapterConfig);
		//$this->tablegateway = $tablegateway;	
	}
	
	public function quizDetails($clientId,$linkId,$code)
	{
		

		$select = "SELECT
									linkDetail.testId AS testId,
									linkDetail.testName AS testName,
									linkDetail.linkId AS linkId,
									setting.custom_instructions_id AS guidline,
									guideline.description AS custom_instruction,
									setting.availability AS availability,
									setting.time_limit AS time,
									setting.display_questions AS queDisplay,
									setting.randomize AS randomize,
									setting.email AS email,
									setting.password AS password,
									setting.firstname AS firstname,
									setting.lastname AS lastname,
									setting.passing_marks AS passMarks,
									setting.total_question as queTotal
								FROM
									(select
										test.id AS testId,
										test.name AS testName,
										link.id AS linkId
									 from
									 	link
									 inner join
									 	test
									 on
									 	link.test_id= test.id
									 where
									 	link.id=$linkId) linkDetail
			
									 INNER JOIN link_settings setting
								ON
									 linkDetail.linkId = setting.link_id 
									 INNER JOIN guidelines guideline
								ON
									guideline.id=setting.custom_instructions_id
									 ";
		
		$result = $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
		  
		return $result;
	}
	public function saveStudent(Student $Student)
	{

		$data = array(
			'fname' => $Student->firstname,
			'lname' => $Student->lastname,
			'email' => $Student->email
		);


		
		$id = (int) $Student->id;
		$email=$Student->email;

		if ($studentArr = $this->getStudent($email)) {

				return $studentArr;
		}
		
		else if ($id ==" "){
			
			$fname = $data['fname'];
			$lname = $data['lname'];
			$email = $data['email'];


			$insert = "INSERT INTO
									student
								VALUES(
										'',
										'$fname',
										'$lname',
										'$email')";

			$result = $this->dbAdapter->query($insert, Adapter::QUERY_MODE_EXECUTE);
			$lastId = $this->dbAdapter->getDriver()->getLastGeneratedValue();
			$arrdata=$this->getname($lastId);
			
			
			
			return $arrdata;
		} 
		
		else {
			throw new \Exception("Error Occurred !!!!!!!!!");
		}	
	}
		
		public function getStudent($email)
		{
			$select = "SELECT
									id,fname,lname,email
								FROM
									student
								WHERE
									email='$email'";

			$result = $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
			$row = $result->current();
			return $row;
		}
		public function getname($id)
		{
			$quizDbSession = new Container('quizDb');
			$select = "SELECT
									id,fname,lname,email
								FROM
									student
								WHERE
									id='$id'";
			$result = $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
			$row = $result->current();
			return $row;

		}
		public function getLinkCodeTime($linkcode,$linkId)
		{
			$select="SELECT 
								showfrom,showuntill 
							FROM 
								link_assign_dates 
							WHERE 
							link_code='$linkcode'
							AND
							link_id = '$linkId'";
			$result = $this->dbAdapter->query($select, Adapter::QUERY_MODE_EXECUTE);
			$row = $result->current();
			return $row;
		}
		public function getArrQuestion($data)
		{
			
			$return ="";
			$testId ="";
			$randomize = "";
			$totalQue = "";
			if(isset($data) && !empty($data)) {
		
				$testId = $data['testId'];
				$totalQue = $data['queTotal'];
				$randomize = $data['randomize'];
				
				
				$arrQue = $this->retrieveQuesForTest($testId);
				
				$arrQueId = array();
				foreach ($arrQue as $x) {
					$arrQueId[] = $x['id'];
				}
				if($randomize == 1) {
					shuffle($arrQueId);
				}
				$return = array_slice($arrQueId, 0,$totalQue);
				
			}

			return $return;
		}
		
		public function retrieveQuesForTest($testId) 
		{
			//$quizDbSession = new Container('quizDb');
			
			$retrieveQuesForTestQuery = "SELECT ques.id,
													ques.description, 
											        cate.name,
											        ques.status 
											FROM   assigned_questions assques 
											       INNER JOIN questions ques 
											               ON assques.ques_id = ques.id 
											       INNER JOIN category cate 
											               ON cate.id = ques.cat_id 
											WHERE  assques.test_id ='$testId'
													AND ques.status=0";

				$result = $this->dbAdapter->query($retrieveQuesForTestQuery, Adapter::QUERY_MODE_EXECUTE);
				$row = $result->toArray();
				return $row;											
		}
		public function loadQuestion($StuIdAndQuestionId)
		{

					$stu_id = $StuIdAndQuestionId[0];
					$questionId = $StuIdAndQuestionId[1];
					$code  =  $StuIdAndQuestionId[2]; 	

					$selectedOptionId = 0;
					$retrieveQuestionQuery = "SELECT ques.id,
											       ques.description,
											       ques.points,
											       ques.type,
											       cat.name AS category,
											       Group_concat(ques_option.id) AS options_id,
											       Group_concat(ques_option.correct) AS correct_options,
											       Group_concat(QUOTE(ques_option.question_option)) AS options
											FROM   questions ques
											       INNER JOIN category cat
											               ON ques.cat_id = cat.id
											       INNER JOIN question_options ques_option
											               ON ques_option.question_id = ques.id
											WHERE  ques.id = '$questionId'
											       AND ques.status = 0";	


					$result = $this->dbAdapter->query($retrieveQuestionQuery, Adapter::QUERY_MODE_EXECUTE);
					$questionsData = (array)$result->current();
					//\Zend\Debug\Debug::dump($result); die;
					$retrieveSelectedOptionQuery = "SELECT selected_option_id
													FROM   attempts
													WHERE  stu_id = '$stu_id'
													       AND ques_id = '$questionId'
														   AND link_code = '$code'";


					
					$result_1 = $this->dbAdapter->query($retrieveSelectedOptionQuery, Adapter::QUERY_MODE_EXECUTE);
					$selectedOptionId = $result_1->toArray();
					
					
						

						if($selectedOptionId===null) {

							$selectedOptionId = 0;	
						}
						//print_r($questionsData);

										       	
													       
					$data = array($questionsData,$selectedOptionId);

					return $data;						

		}
		
		public function loadAttemptTime($StuIdAndQuestionId)
		{
			$stu_id		= $StuIdAndQuestionId[0];
			$code		= $StuIdAndQuestionId[1];
		
			$retrieveElapsedTimeQuery = "SELECT	max(elapsed_time) as elapsed
										 FROM 	attempts
										 WHERE	link_code	= '$code'
										 AND	stu_id		= '$stu_id'";
			
			$result = $this->dbAdapter->query($retrieveElapsedTimeQuery, Adapter::QUERY_MODE_EXECUTE);
			
			$ElapsedTime = (array)$result->current();
		
			
					if($ElapsedTime['elapsed'] === null) {
					$ElapsedTime = 0;
					}
					else { $ElapsedTime = $ElapsedTime['elapsed']; }
		
		
		return $ElapsedTime;
		}
		
		public function addSelectedOption($arrData)
		{
			//\Zend\Debug\Debug::dump($arrData);
			if(is_array($arrData[2]))
			{

			foreach ($arrData[2] as $key => $value) {
				
				# code...
			
			
			$return = 0;
				$insertIntoAttemptsQuery = "INSERT INTO attempts
											            (link_code,
											             stu_id,
											             ques_id,
											             selected_option_id,
											             elapsed_time)
											VALUES     ('$arrData[3]',
											            '$arrData[0]',
											            '$arrData[1]',
											            '$value',
											            '$arrData[4]')";

			$result = $this->dbAdapter->query($insertIntoAttemptsQuery, Adapter::QUERY_MODE_EXECUTE);
			}
			$val_1=$this->dbAdapter->getDriver()->getLastGeneratedValue();
			
			}
			else
			{
				$return = 0;
				$insertIntoAttemptsQuery = "INSERT INTO attempts
											            (link_code,
											             stu_id,
											             ques_id,
											             selected_option_id,
											             elapsed_time)
											VALUES     ('$arrData[3]',
											            '$arrData[0]',
											            '$arrData[1]',
											            '$arrData[2]',
											            '$arrData[4]')";

			$result = $this->dbAdapter->query($insertIntoAttemptsQuery, Adapter::QUERY_MODE_EXECUTE);
			
			$val_1=$this->dbAdapter->getDriver()->getLastGeneratedValue();
			}
			if($val_1)
			{
				$return = 1;
			}
		return $return;
		}

		public function resultProcessing($arrData) 
		{

				$resultProcessingQuery = 	"SELECT DISTINCT attmp.ques_id AS quesid,
											                 Group_concat(ques_option.correct) AS correct_counts,
											                 ques.points
											 FROM   		 question_options ques_option
											      INNER JOIN attempts attmp
											              ON ques_option.id = attmp.selected_option_id
											      INNER JOIN questions ques
											              ON attmp.ques_id = ques.id
											 WHERE  		 attmp.stu_id = '$arrData[1]'
											       AND 		 attmp.link_code = '$arrData[0]'
											       AND 		 correct = '1'
															 GROUP  BY quesid;";  
	

				$result = $this->dbAdapter->query($resultProcessingQuery, Adapter::QUERY_MODE_EXECUTE);
				$resultForStudent = $result->toArray();	
				
				return $resultForStudent;			
		}

		public function getTestTotalMarks($arrData)
		{
				$resultProcessingQuery = 	"SELECT
														SUM(points) as points
											 FROM   		 
														questions
											 WHERE		
														id in($arrData[2])";  
				
				$result = $this->dbAdapter->query($resultProcessingQuery, Adapter::QUERY_MODE_EXECUTE);
				$resultForStudent = $result->toArray();	
				return $resultForStudent;			
		}
		public function result_store($arrData)
		{
		
			$score=$arrData[3][2];

			$totalQuestion = $arrData[3][1];
			
			$totalscore = $arrData[7][0]['points'];
			$percentage = round(($score/$totalscore)*100);
			$date_started=$arrData[5];
			
			$date_finished=$arrData[6];
			$date_finished= $date_finished->format("Y-m-d H:i:s");
			 
			$time1 = strtotime($date_started);
			$time2 = strtotime($date_finished);
       
			$duration=$time2-$time1-1;
			
			$fetchLinkIdQuery="SELECT id 
								FROM link_assign_dates
								WHERE link_id='$arrData[0]'
								AND link_code='$arrData[1]'";
			$result = $this->dbAdapter->query($fetchLinkIdQuery, Adapter::QUERY_MODE_EXECUTE);
			$linkID = (array)$result->current();

			/*\Zend\Debug\Debug::dump($linkID);
			die;*/
			$InsertResultQuery="INSERT INTO result
											            (link_assign_dates_id,
											             student_id,
											             score,
											             totalscore,
											             percentage,
											             duration,
											             date_started,
											             date_finished)
											VALUES     ('$linkID[id]',
											            '$arrData[2]',
											            '$score',
											            '$totalscore',
											            '$percentage',
											            '$duration',
											            '$date_started',
											            '$date_finished')";	
			$result_1 = $this->dbAdapter->query($InsertResultQuery, Adapter::QUERY_MODE_EXECUTE);
			//$res = $this->dbAdapter->getDriver()->getLastGeneratedValue();

			$FetchResultQuery="SELECT student_id,
										score,
										percentage,
										duration,
										date_started,
										date_finished,
										totalscore
								FROM result where student_id='$arrData[2]' 
								AND link_assign_dates_id='$linkID[id]' group by student_id AND link_assign_dates_id";
			$result_2=$this->dbAdapter->query($FetchResultQuery, Adapter::QUERY_MODE_EXECUTE);
			$res=$result_2->toArray();
			
			
			return $res;
		}
		public function fetch_data($arrData)
		{
			$fetchLinkIdQuery="SELECT id 
								FROM link_assign_dates
								WHERE link_id='$arrData[0]'
								AND link_code='$arrData[1]'";
			$result = $this->dbAdapter->query($fetchLinkIdQuery, Adapter::QUERY_MODE_EXECUTE);
			$linkID = (array)$result->current();
			$FetchResultQuery="SELECT student_id,
										score,
										percentage,
										duration,
										date_started,
										date_finished,
										totalscore
								FROM result where student_id='$arrData[2]' 
								AND link_assign_dates_id='$linkID[id]' group by student_id AND link_assign_dates_id";
			$result_2=$this->dbAdapter->query($FetchResultQuery, Adapter::QUERY_MODE_EXECUTE);
			$res=$result_2->toArray();	
			
			return $res;
		}
		public function delete_preview_rows()
		{
			$deleteQuery="DELETE from attempts 
							WHERE stu_id='0' 
							AND link_code='preview'";
			$result = $this->dbAdapter->query($deleteQuery, Adapter::QUERY_MODE_EXECUTE);
			//$res = (array)$result->current();
			return $res;
		}
	
	
}