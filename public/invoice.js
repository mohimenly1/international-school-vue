document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('id');

    try {
        const response = await fetch(`http://127.0.0.1:8000/api/students/${studentId}`);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const studentDetails = await response.json();
        const invoiceWrapper = document.getElementById('invoice_wrapper');

        const qrCodeUrl = `http://api.qrserver.com/v1/create-qr-code/?data=Student%20Number:%20${studentDetails.id}%0AStudent's%20Name:%20${studentDetails.name}%0AAddress:%20${studentDetails.address}%0AParent's%20Phone:%20${studentDetails.parent?.phone_number_one ?? 'N/A'}&size=100x100`;

        // Ensure amounts are numbers
        const totalAmount = studentDetails.subscription_fees.reduce((acc, fee) => {
            return acc + parseFloat(fee.amount) || 0; // Convert to float and default to 0 if NaN
        }, 0);

        invoiceWrapper.innerHTML = `
        <style>
        @font-face {
            font-family: 'Cairo';
            src: url('/fonts/Cairo-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cairo';
            src: url('/fonts/Cairo-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Cairo';
            src: url('/fonts/Cairo-SemiBold.ttf') format('truetype');
            font-weight: 600;
            font-style: normal;
        }
        </style>
        <div class="invoice-info clearfix">
            <div class="invoice-header">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice-logo">
                            <div class="logo" style="text-align:center">
                                <img style="width:130px;height:130px;" src="./logo-school-one.png" alt="logo">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="invoice-id">
                            <div class="info">
                                <h2 class="inv-header-1">Caledonian International School</h2>
                                <p class="mb-1">Student Number: <span>#${studentDetails.id}</span></p>
                                <p class="mb-0">Date: <span>${new Date().toLocaleDateString()}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-top">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice-number mb-30">
                            <h4 class="inv-title-1">Best Regards /</h4>
                            <p>Student's name / <span style="font-weight:600; font-family: 'Cairo', sans-serif;">${studentDetails.name}</span></p>
                            <p class="invo-addr-1">
                                Address, <span style="font-weight:600; font-family: 'Cairo', sans-serif;">${studentDetails.address}</span> <br/>
                                Parent's Phone, <b class="text-600">${studentDetails.parent?.phone_number_one ?? 'N/A'}</b>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="invoice-number mb-30">
                            <div class="invoice-number-inner">
                                <h4 class="inv-title-1">Contact Us / </h4>
                                <p class="invo-addr-1">
                                    <img style="width:17px; height:17px; margin-right:8px;" src="./location.png" /> Qarji behind the pasta factory
                                    <br/>
                                    <img style="width:17px; height:17px; margin-right:8px;" src="./mail.png" /> info@caledonian.ly <br/>
                                    <img style="width:17px; height:17px; margin-right:8px;" src="./phone-call.png" /> 0916676973 <br/>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-center">
                <div class="table-responsive">
                    <table class="table mb-0 table-striped invoice-table">
                        <thead class="bg-active">
                            <tr class="tr">
                                <th>No.</th>
                                <th class="pl0 text-start">Item Description</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${studentDetails.subscription_fees.map((fee, index) => `
                                <tr class="${index % 2 === 0 ? 'bg-grea' : ''}">
                                    <td>
                                        <div class="item-desc-1">
                                            <span>${index + 1}</span>
                                        </div>
                                    </td>
                                    <td class="pl0">${fee.category} - ${fee.sub_category}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-end">LYD ${fee.amount}</td>
                                </tr>
                            `).join('')}
                            <tr class="tr2">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center f-w-600 active-color">Grand Total</td>
                                <td class="f-w-600 text-end active-color">LYD ${totalAmount.toFixed(2)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="invoice-bottom">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice-qr">
                            <img src="${qrCodeUrl}" alt="QR Code" />
                        </div>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="parent-signature">
                            <p style="margin-top:80px; margin-left:140px;">Parent's Signature: ................................</p>
                            <div class="signature-box"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="invoice-contact clearfix">
                <div class="row g-0">
                    <div class="col-sm-12">
                        <div class="contact-info clearfix">
                            <a href="tel:+55-4XX-634-7071" class="contact-item"><i class="fa fa-phone"></i>  0916676973 </a>
                            <a href="mailto:info@themevessel.com" class="contact-item"><i class="fa fa-envelope"></i>  info@caledonian.ly</a>
                            <a href="#" class="contact-item"><i class="fa fa-map-marker"></i> Qarji behind the pasta factory </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .contact-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin: 10px;
            text-decoration: none;
            color: inherit; /* Adjust as per your design */
        }

        .contact-item i {
            margin-right: 5px; /* Space between icon and text */
        }
        </style>
        `;
    } catch (error) {
        console.error('Error fetching student details:', error);
        alert('Failed to fetch student details');
    }
});

function generatePDF() {
    const invoiceWrapper = document.getElementById('invoice_wrapper');

    html2canvas(invoiceWrapper, { scale: 3 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 210; // A4 width in mm
        const pageHeight = 295; // A4 height in mm
        const imgHeight = canvas.height * imgWidth / canvas.width;

        // Calculate scaling factor to fit content in one page
        const scalingFactor = Math.min(imgWidth / canvas.width, pageHeight / imgHeight);
        
        // Calculate new dimensions
        const scaledImgWidth = canvas.width * scalingFactor;
        const scaledImgHeight = canvas.height * scalingFactor;

        pdf.addImage(imgData, 'PNG', 0, 0, scaledImgWidth, scaledImgHeight);
        pdf.save('invoice.pdf');
    });
}
