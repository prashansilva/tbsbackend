<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            Filtering feilds
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Document Code</th>
                        <th scope="col">Manager Code</th>
                        <th scope="col">Line Leader Code</th>
                        <th scope="col">Coordinator Code</th>
                        <th scope="col">Discussor</th>
                        <th scope="col">Prospector</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">Created Date</th>
                    </tr>
                </thead>
                <tbody id="discussion_document_list">
                    <?php foreach ($documents as $document) { ?>
                       
                        <tr>
                            <td><?php echo $document['code']; ?></td>
                            <td><?php echo $document['manager_id']; ?></td>
                            <td><?php echo $document['line_leader_id']; ?></td>
                            <td><?php echo $document['coordinator_id']; ?></td>
                            <td><?php echo $document['discusser']; ?></td>
                            <td><?php echo $document['prospector']; ?></td>
                            <td><?php echo $document['mobile_number']; ?></td>
                            <td><?php echo $document['create_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>