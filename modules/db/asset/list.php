<?

	include $_SERVER["DOCUMENT_ROOT"]."/library/db.php";


	$conn = init_sql();

	$output = array();

	$session = $_GET["session"];

	if (isset($_GET["meta"]) && $_GET["meta"] != "" && $_GET["meta"] != "ALL") {

		$meta = $_GET["meta"];

		$query = "SELECT ACTOR_ID, ACTORS.NAME AS ACTOR_NAME, ACTORS.ASSET_ID, ASSETS.NAME AS ASSET_NAME, ASSET_TYPES.NAME AS ASSET_TYPE_NAME, ASSET_TYPES.ASSET_TYPE_ID AS ASSET_TYPE_ID, RX, RY, ACTORS.META, ACTORS.TAG, ACTORS.GROUP AS ACTOR_GROUP FROM ACTORS INNER JOIN ASSETS ON ASSETS.ASSET_ID = ACTORS.ASSET_ID INNER JOIN ASSET_TYPES ON ASSET_TYPES.ASSET_TYPE_ID = ASSETS.ASSET_TYPE_ID WHERE ACTORS.SESSION = '$session' AND (ACTORS.META = '$meta' OR ACTORS.META = 'MAIN' OR ACTORS.META = 'ALL' OR ACTORS.META = 'SCENE') ORDER BY ACTOR_ID ASC";

	} else {

		$query = "SELECT ACTOR_ID, ACTORS.NAME AS ACTOR_NAME, ACTORS.ASSET_ID, ASSETS.NAME AS ASSET_NAME, ASSET_TYPES.NAME AS ASSET_TYPE_NAME, ASSET_TYPES.ASSET_TYPE_ID AS ASSET_TYPE_ID, RX, RY, ACTORS.META, ACTORS.TAG, ACTORS.GROUP AS ACTOR_GROUP FROM ACTORS INNER JOIN ASSETS ON ASSETS.ASSET_ID = ACTORS.ASSET_ID INNER JOIN ASSET_TYPES ON ASSET_TYPES.ASSET_TYPE_ID = ASSETS.ASSET_TYPE_ID WHERE ACTORS.SESSION = '$session' AND (ACTORS.META IS NULL OR (ACTORS.META != 'POSE' AND ACTORS.META != 'FACE' AND ACTORS.META != 'BODY' AND ACTORS.META != 'OBJECT' AND ACTORS.META != 'FIGURE' AND ACTORS.META != 'OUTLINE')) ORDER BY ACTOR_ID ASC";

	}


	$result = $conn->query($query);

    while ($row = $result->fetch_object()){

        if ($row->ASSET_NAME == "Generic" && $row->ACTOR_GROUP != null && $_GET["meta"] != "SCENE") {

            continue;

        }

        $output[$row->ACTOR_ID] = $row;
    }

    $result->close();

    echo json_encode($output);	

?>