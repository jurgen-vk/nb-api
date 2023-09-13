import csv

# Input and output file paths
input_file = 'input.csv'
output_file = 'output.txt'

# Open input and output files
with open(input_file, 'r', newline='') as csvfile_in, open(output_file, 'w', newline='') as csvfile_out:
    csv_reader = csv.reader(csvfile_in, delimiter=',')
    csv_writer = csv.writer(csvfile_out, delimiter=';')

    for row in csv_reader:
        if len(row) == 5:  # Check for the correct number of columns
            col1 = row[0]
            col2 = row[1]
            col3 = row[2]
            col4 = row[3]
            col5 = row[4]

            # Format the location column
            location = f'Point({col4} {col5})'

            # Write the formatted row to the output CSV
            csv_writer.writerow([col1, col2, col3, location])
