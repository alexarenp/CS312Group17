<?php include 'header.php'; ?>

<header>
    <h1>Color Coordinator</h1>
</header>

    <form action="color.php" method="GET">
        Rows and Columns: <input type="number" name="gridSize" placeholder="Enter grid size (1-26)" required>
        <br>
        Colors: <input type="number" name="numColors" placeholder="Enter num colors (1-10)" required>
        <br>
        <button type="submit">Submit</button>
    </form>

    <div id="duplicate-msg">That color is already in use. Your selection has been reverted.</div>
        <?php
        $validGridSize = false;
        $validNumColors = false;
        if(isset($_GET['gridSize'])) { 
            $gridSize = $_GET['gridSize'];
            if($gridSize < 1 || $gridSize > 26) {
                echo "Not able to create a table with these values. Please input values between 1 and 26. <br>";
                $validGridSize = false;
            }
            else {
                $validGridSize = true;
            }
        }
        if(isset($_GET['numColors'])) {
            $numColors = $_GET['numColors'];
            if($numColors < 1 || $numColors > 10) {
                echo "Not able to create a table with these values. Please specify a number of colors between 1 and 10. <br>";
                $validNumColors = false;
            }
            else {
                $validNumColors = true;
            }
        }
        $colors = ["Red", "Orange", "Yellow", "Green", "Blue", "Purple", "Grey", "Brown", "Black", "Teal"];
        $alphabet = range('A', 'Z');
        if($validGridSize && $validNumColors) {
            echo "<h2>Color Selection</h2>";
            echo "<table class='color-table'>";
            for($i = 0; $i < $numColors; $i++) {
                $defaultColor = $colors[$i];
                echo "<tr>";
                echo "<td>";
                if ($i == 0) {
                    echo "<input type='radio' name='activeColor' value='$i' checked>";
                } else {
                    echo "<input type = 'radio' name = 'activeColor' value='$i'>";
                }
                echo "<select name='color$i' id='color$i' data-index='$i' onchange='updateColor(this)'>"; 
                foreach($colors as $index => $color) {
                    $selected = ($index == $i) ? "selected" : "";
                    echo "<option value='$color' $selected>$color</option>";
                }
                echo '</select>';
                echo "</td>";
                echo "<td id='coords$i'></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<h2>Coordinate Grid</h2>";
            echo "<table class='coordinate-grid'>";
            echo "<tr>";
            echo "<td></td>";
            for($col = 0; $col < $gridSize; $col++) {
                echo "<td>" . $alphabet[$col] . "</td>";
            }
            echo "</tr>";
            for($row = 0; $row < $gridSize; $row++) {
                echo "<tr>";
                echo "<td>" . ($row + 1) . "</td>";
                for($col = 0; $col < $gridSize; $col++) {
                    $coordinate = $alphabet[$col] . ($row + 1);
                    echo "<td class= 'paint-cell' data-coordinate='$coordinate'></td>";
                    
                }
                echo "</tr>";
            }
            echo "</table>";
            ?>
            <form action="print.php" method="POST">
                <button class="makeprint" type="submit" name="printButton">Print</button>
            </form>
            <?php   
        }
        if(isset($_POST["printButton"])) {
            header("Location: print.php");
            exit();
        }
    ?>


    <script>
        let indexInGrid = 0;
        const coordsList = {};
        const trackCells = {};

        function sortCoords(a,b) {
            const Aletter= a.match(/[A-Z]+/)[0];
            const Anum = parseInt(a.match(/\d+/)[0]);

            const Bletter = b.match(/[A-Z]+/)[0];
            const Bnum = parseInt(b.match(/\d+/)[0]);

            if (Aletter !== Bletter) {
                return Aletter.localeCompare(Bletter);
            }
            return Anum - Bnum;
        }

        function newCoordGenerator(rowIndex) {
            const list = coordsList[rowIndex] || [];
            list.sort(sortCoords);
            document.getElementById('coords' + rowIndex).textContent = list.join(', ');
        }

        const colorMap = {
            "Red":    "#e74c3c",
            "Orange": "#e67e22",
            "Yellow": "#f1c40f",
            "Green":  "#27ae60",
            "Blue":   "#2980b9",
            "Purple": "#8e44ad",
            "Grey":   "#95a5a6",
            "Brown":  "#7f5539",
            "Black":  "#2c2c2c",
            "Teal":   "#008080"
        };
 
        const previousValues = {};
        document.querySelectorAll('select[id^="color"]').forEach(function(sel) {
            const idx = sel.dataset.index;
            previousValues[idx] = sel.value;

        });
        document.querySelectorAll('input[name="activeColor"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                indexInGrid = this.value;
            });
        });
        document.querySelectorAll('.paint-cell').forEach(function(cell) {
            cell.addEventListener('click', function() {
                const coordinate = this.dataset.coordinate;
                const oldOwner = trackCells[coordinate];
                if (oldOwner !== undefined && oldOwner !== indexInGrid) {
                    coordsList[oldOwner] = coordsList[oldOwner].filter(function(item) {
                        return item !== coordinate;
                    });
                    newCoordGenerator(oldOwner);
                }

                if (!coordsList[indexInGrid]) {
                    coordsList[indexInGrid] = [];
                }
                if (!coordsList[indexInGrid].includes(coordinate)){
                    coordsList[indexInGrid].push(coordinate);
                }

                trackCells[coordinate] = indexInGrid;

                const selectedBox = document.getElementById('color' + indexInGrid);
                const currentColor = selectedBox.value;
                this.style.backgroundColor = colorMap[currentColor];

                newCoordGenerator(indexInGrid);
                
            });
        });


 
        function updateColor(selectEl) {
            const idx = selectEl.dataset.index;
            const newColor = selectEl.value;
            const msgEl = document.getElementById('duplicate-msg');
 
            let duplicate = false;
            document.querySelectorAll('select[id^="color"]').forEach(function(other) {
                if (other !== selectEl && other.value === newColor) {
                    duplicate = true;
                }
            });
 
            if (duplicate) {
                selectEl.value = previousValues[idx];
                msgEl.style.display = 'block';
                setTimeout(function() { msgEl.style.display = 'none'; }, 3500);
            } else {
                previousValues[idx] = newColor;

                msgEl.style.display = 'none';

                if (coordsList[idx]){
                    coordsList[idx].forEach(function(coordinate) {
                        const cell = document.querySelector('[data-coordinate="' + coordinate + '"]');
                        if (cell) {
                            cell.style.backgroundColor = colorMap[newColor];
                        }
                    })
                }
            }
        }

        document.querySelector('form[action="print.php"]').addEventListener('submit', function(e) {
            e.preventDefault();

            // Grab gridSize and numColors already in the URL
            const params = new URLSearchParams(window.location.search);

            // Add each currently selected color
            document.querySelectorAll('select[id^="color"]').forEach(function(sel) {
                params.set(sel.name, sel.value);
            });

            document.querySelectorAll('td[id^="coords"]').forEach(function(cell) {
                params.set(cell.id, cell.textContent);});


            window.location.href = 'print.php?' + params.toString();
        });
    </script>

<?php include 'footer.php'; ?>

