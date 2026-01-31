<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqEnglishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqsEnglish = [
            [
                'question' => 'Bagaimana cara melakukan booking kendaraan?',
                'question_en' => 'How to book a vehicle?',
                'answer_en' => '<p>To book a vehicle, you can:</p><ol><li>Select the desired vehicle from the <strong>Fleet</strong> page</li><li>Click the <strong>Book Now</strong> button</li><li>Fill out the booking form completely (start date, duration, and renter data)</li><li>Confirm booking and wait for confirmation from admin</li></ol><p>After booking is confirmed, you will receive a confirmation email with booking details.</p>',
            ],
            [
                'question' => 'Apa saja syarat dan ketentuan untuk menyewa kendaraan?',
                'question_en' => 'What are the terms and conditions for renting a vehicle?',
                'answer_en' => '<p>Terms and conditions for renting a vehicle:</p><ul><li>Have a <strong>valid driver\'s license</strong> according to vehicle type</li><li>Minimum age <strong>21 years</strong></li><li>Provide <strong>original ID card</strong> and photocopy</li><li>Pay <strong>minimum 50% down payment</strong> of total cost</li><li>Fill out rental form with valid data</li><li>Agree to applicable terms and conditions</li></ul>',
            ],
            [
                'question' => 'Berapa lama proses konfirmasi booking?',
                'question_en' => 'How long does the booking confirmation process take?',
                'answer_en' => '<p>Booking confirmation process usually takes <strong>1-2 hours</strong> on weekdays (Monday-Friday, 08:00-17:00 WIB).</p><p>For bookings outside business hours or holidays, confirmation will be processed on the next business day.</p><p>You will receive notification via email or WhatsApp after booking is confirmed.</p>',
            ],
            [
                'question' => 'Apakah tersedia layanan dengan sopir?',
                'question_en' => 'Is driver service available?',
                'answer_en' => '<p>Yes, we provide professional driver service. Our drivers:</p><ul><li>Experienced and have <strong>valid driver\'s license</strong></li><li>Know routes in operational areas</li><li>Friendly and professional in service</li><li>Can assist as <strong>tour guide</strong> if needed</li></ul><p>Driver fees can be seen in the price details of each vehicle. Please select the <strong>With Driver</strong> option when booking.</p>',
            ],
            [
                'question' => 'Bagaimana sistem pembayaran sewa kendaraan?',
                'question_en' => 'How is the vehicle rental payment system?',
                'answer_en' => '<p>Vehicle rental payment system:</p><ol><li><strong>Down Payment (DP)</strong>: Minimum 50% of total cost when booking is confirmed</li><li><strong>Final Payment</strong>: Remaining payment is made when picking up the vehicle</li><li><strong>Payment methods</strong>: Bank transfer, cash, or e-wallet</li><li><strong>Deposit</strong>: Deposit required as guarantee (will be returned after vehicle is returned in good condition)</li></ol>',
            ],
            [
                'question' => 'Apakah kendaraan sudah termasuk bahan bakar?',
                'question_en' => 'Is fuel included with the vehicle?',
                'answer_en' => '<p>Vehicles are delivered with <strong>full tank</strong>. Upon return, the vehicle must be returned with full tank as well.</p><p>If the vehicle is returned with less fuel than when picked up, additional charges will apply according to the fuel difference.</p><p>Fuel costs during rental are the <strong>renter\'s responsibility</strong>.</p>',
            ],
            [
                'question' => 'Bagaimana jika terjadi kerusakan pada kendaraan selama sewa?',
                'question_en' => 'What if damage occurs to the vehicle during rental?',
                'answer_en' => '<p>If damage occurs to the vehicle during rental:</p><ul><li><strong>Minor damage</strong>: Repair costs are borne by the renter according to repair costs at official workshops</li><li><strong>Major damage</strong>: Assessment will be conducted first to determine repair costs</li><li><strong>Insurance</strong>: We provide insurance options to protect against damage risks (optional, with additional cost)</li><li><strong>Police report</strong>: For accidents, police report is mandatory</li></ul>',
            ],
            [
                'question' => 'Bisakah melakukan booking untuk jangka waktu panjang (bulanan)?',
                'question_en' => 'Can I book for long-term (monthly)?',
                'answer_en' => '<p>Yes, we serve long-term vehicle rental with special prices:</p><ul><li><strong>Daily rental</strong>: Standard price per day</li><li><strong>Weekly rental</strong>: 10% discount from total daily price</li><li><strong>Monthly rental</strong>: 20% discount from total daily price</li></ul><p>For long-term rental, please contact our customer service to get the best price offer. We also provide special packages for corporate needs.</p>',
            ],
            [
                'question' => 'Apakah bisa membatalkan atau mengubah booking?',
                'question_en' => 'Can I cancel or change my booking?',
                'answer_en' => '<p>Cancellation or booking changes can be made with the following terms:</p><ul><li><strong>Cancellation 3 days before rental date</strong>: DP refunded 100%</li><li><strong>Cancellation 1-2 days before rental date</strong>: DP refunded 50%</li><li><strong>Cancellation on rental day</strong>: DP cannot be refunded</li><li><strong>Date change</strong>: Can be done maximum 2 days before rental date (depending on vehicle availability)</li></ul><p>For cancellation or changes, please contact our customer service.</p>',
            ],
            [
                'question' => 'Dimana lokasi pengambilan dan pengembalian kendaraan?',
                'question_en' => 'Where is the vehicle pickup and return location?',
                'answer_en' => '<p>Vehicle pickup and return locations:</p><ul><li><strong>Main office</strong>: [Full Address] - Open daily 08:00-17:00 WIB</li><li><strong>Delivery service</strong>: We provide vehicle pickup and return service with additional cost (depending on distance)</li><li><strong>Airport</strong>: Pickup/return at airport can be arranged with additional cost</li></ul><p>For more detailed information about locations and delivery service, please contact our customer service at <strong>+62 821 7086 0825</strong> or email to <strong>info@dsarana.com</strong>.</p>',
            ],
        ];

        foreach ($faqsEnglish as $faqData) {
            $faq = Faq::where('question', $faqData['question'])->first();
            if ($faq) {
                $faq->update([
                    'question_en' => $faqData['question_en'],
                    'answer_en' => $faqData['answer_en'],
                ]);
                $this->command->info("Updated FAQ: {$faqData['question_en']}");
            }
        }

        $this->command->info('FAQ English translations added successfully!');
    }
}
