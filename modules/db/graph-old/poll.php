<?

	include $_SERVER["DOCUMENT_ROOT"]."/library/db.php";

	$conn = init_sql();

	$output = array();


    $query = "SELECT ACTORS.ACTOR_ID, ACTORS.NAME AS ACTOR_NAME, ASSETS.NAME AS ASSET_NAME, ASSET_TYPES.NAME AS ASSET_TYPE_NAME, GRAPH_X, GRAPH_Y, X, Y, Z, ATTRIBUTES.ATTRIBUTE_ID AS ATTRIBUTE_ID, ATTRIBUTE_TYPES.NAME AS ATTRIBUTE_TYPE_NAME, ATTRIBUTES.ATTRIBUTE_TYPE_ID, `LABEL`, `VALUE`, I0.* FROM ACTORS
INNER JOIN ASSETS ON ASSETS.ASSET_ID = ACTORS.ASSET_ID
INNER JOIN ASSET_TYPES ON ASSET_TYPES.ASSET_TYPE_ID = ASSETS.ASSET_TYPE_ID
LEFT JOIN ATTRIBUTES ON ATTRIBUTES.ACTOR_ID = ACTORS.ACTOR_ID
LEFT JOIN ATTRIBUTE_TYPES ON ATTRIBUTE_TYPES.ATTRIBUTE_TYPE_ID = ATTRIBUTES.ATTRIBUTE_TYPE_ID

LEFT JOIN INTERACTIONS I0 ON I0.ACTOR_ID_0 = ACTORS.ACTOR_ID

ORDER BY ACTOR_ID ASC";
    $result = $conn->query($query);

    $output = array();
    $output["ACTORS"] = array();
    $output["ACTORS"]["CHARACTER"] = array();
    $output["ACTORS"]["PROP"] = array();
    $output["ACTORS"]["ACTION"] = array();
    $output["ACTORS"]["POSITION"] = array();
    $output["ACTORS"]["CAMERA"] = array();

    $output["ATTRIBUTES"] = array();
    $output["INTERACTIONS"] = array();

    while ($row = $result->fetch_object()){
        $output["ACTORS"][$row->ASSET_TYPE_NAME][$row->ACTOR_ID] = $row;

        if ($row->ATTRIBUTE_ID != "") {

            $output["ATTRIBUTES"][$row->ACTOR_ID][$row->ATTRIBUTE_ID] = $row;

        }

        if ($row->INTERACTION_ID != "") {

            $output["INTERACTIONS"][$row->INTERACTION_ID] = $row;

        }
    }


    $result->close();

    echo json_encode($output);

?>