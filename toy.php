<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';

	// Retrieve the value of the 'toynum' parameter from the URL query string
	//		i.e., ../toy.php?toynum=0001
	$toy_id = $_GET['toynum'];


	/*
	 * TO-DO: Define a function that retrieves ALL toy and manufacturer info from the database based on the toynum parameter from the URL query string.
	 		  - Write SQL query to retrieve ALL toy and manufacturer info based on toynum
	 		  - Execute the SQL query using the pdo function and fetch the result
	 		  - Return the toy info

	 		  Retrieve info about toy from the db using provided PDO connection
	 */
	function get_toy_info(PDO $pdo, string $id) {
		// SQL query to retrieve only toy information since the manufacturers table doesn't exist
		$sql = "SELECT * 
				FROM toy 
				WHERE toynum = :id;";

		// Execute the SQL query and fetch the result
		$toy_info = pdo($pdo, $sql, ['id' => $id])->fetch();

		// Return the toy information
		return $toy_info;
	}

	// Retrieve toy info using the new function
	$toy_info = get_toy_info($pdo, $toy_id);

	// Add a new function to retrieve both toy and manufacturer info
	function get_toy_and_manuf_info(PDO $pdo, string $id) {
		// SQL query to retrieve toy and manufacturer information by joining the toy and manuf tables
		// Concatenate street, city, state, and zipcode into a single address field
		$sql = "SELECT toy.*, 
				manuf.name AS manuf_name, 
				CONCAT(manuf.street, ', ', manuf.city, ', ', manuf.state, ' ', manuf.zipcode) AS manuf_Street, 
				manuf.phone AS manuf_phone, 
				manuf.contact AS manuf_contact
				FROM toy
				LEFT JOIN manuf ON toy.manid = manuf.manid
				WHERE toy.toynum = :id;";

		// Execute the SQL query and fetch the result
		$toy_info = pdo($pdo, $sql, ['id' => $id])->fetch();

		// Ensure all expected keys exist to avoid undefined key warnings
		$toy_info_safe = [
			'imgSrc' => isset($toy_info['imgSrc']) ? $toy_info['imgSrc'] : '',
			'name' => isset($toy_info['name']) ? $toy_info['name'] : '',
			'description' => isset($toy_info['description']) ? $toy_info['description'] : '',
			'price' => isset($toy_info['price']) ? $toy_info['price'] : '',
			'agerange' => isset($toy_info['agerange']) ? $toy_info['agerange'] : 'N/A',
			'numinstock' => isset($toy_info['numinstock']) ? $toy_info['numinstock'] : 'N/A',
			'manuf_name' => isset($toy_info['manuf_name']) ? $toy_info['manuf_name'] : '',
			'manuf_Street' => isset($toy_info['manuf_Street']) ? $toy_info['manuf_Street'] : '',
			'manuf_phone' => isset($toy_info['manuf_phone']) ? $toy_info['manuf_phone'] : '',
			'manuf_contact' => isset($toy_info['manuf_contact']) ? $toy_info['manuf_contact'] : ''
		];

		// Return the toy and manufacturer information with safe defaults
		return $toy_info_safe;
	}

	// Retrieve toy and manufacturer info using the new function
	$toy_info = get_toy_and_manuf_info($pdo, $toy_id);

// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Toys R URI</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>

		<header>
			<div class="header-left">
				<div class="logo">
					<img src="imgs/logo.png" alt="Toy R URI Logo">
      			</div>

	      		<nav>
	      			<ul>
	      				<li><a href="index.php">Toy Catalog</a></li>
	      				<li><a href="about.php">About</a></li>
			        </ul>
			    </nav>
		   	</div>

		    <div class="header-right">
		    	<ul>
		    		<li><a href="order.php">Check Order</a></li>
		    	</ul>
		    </div>
		</header>

		<main>
			<!-- 
			  -- TO DO: Fill in ALL the placeholders for this toy from the db
  			  -->
			
			<div class="toy-details-container">
				<div class="toy-image">
					<!-- Display image of toy with its name as alt text -->
					<img src="<?= htmlspecialchars($toy_info['imgSrc']) ?>" alt="<?= htmlspecialchars($toy_info['name']) ?>">
				</div>

				<div class="toy-details">

					<!-- Display name of toy -->
			        <h1><?= htmlspecialchars($toy_info['name']) ?></h1>

			        <hr />

			        <h3>Toy Information</h3>

			        <!-- Display description of toy -->
			        <p><strong>Description:</strong> <?= htmlspecialchars($toy_info['description']) ?></p>

			        <!-- Display price of toy -->
			        <p><strong>Price:</strong> $ <?= htmlspecialchars($toy_info['price']) ?></p>

			        <!-- Display age range of toy -->
			        <p><strong>Age Range:</strong> <?= htmlspecialchars($toy_info['agerange']) ?></p>

			        <!-- Display stock of toy -->
			        <p><strong>Number In Stock:</strong> <?= htmlspecialchars($toy_info['numinstock']) ?></p>

			        <br />

			        <h3>Manufacturer Information</h3>

			        <!-- Display name of manufacturer -->
			        <p><strong>Name:</strong> <?= htmlspecialchars($toy_info['manuf_name'])  ?> </p>

			        <!-- Display address of manufacturer -->
			        <p><strong>Address:</strong> <?= htmlspecialchars($toy_info['manuf_Street'])  ?></p>

			        <!-- Display phone of manufacturer -->
			        <p><strong>Phone:</strong> <?= htmlspecialchars($toy_info['manuf_phone'])  ?></p>

			        <!-- Display contact of manufacturer -->
			        <p><strong>Contact:</strong> <?= htmlspecialchars($toy_info['manuf_contact'])  ?></p>
			    </div>
			</div>
		</main>

	</body>
</html>