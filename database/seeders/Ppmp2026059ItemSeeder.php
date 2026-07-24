<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Material;
use App\Models\ProcurementClassification;
use App\Models\ProcurementPlan;
use App\Models\ProcurementPlanItem;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class Ppmp2026059ItemSeeder extends Seeder
{
    /**
     * Real FY2026 PPMP-2026-059 A, Carmona Campus — transcribed from the
     * BOR-approved source document (Other docs/F164 - CARMONA - PPMP-2026-059 A (1).pdf,
     * pages 5-15, the itemized detail behind the category summary on pages 1-4).
     *
     * Verified, not just eyeballed: the first 43 rows (PS-DBM group) sum to exactly
     * the document's own PS-DBM MOOE subtotal (192,791.55), and the last 5 rows
     * (CO group) sum to exactly its Capital Outlay subtotal (841,029.42). The
     * middle NON-PS/MOOE block is checked against its subtotal (15,150,126.04)
     * and the grand total (16,183,947.01) at the end of run().
     *
     * Each row: [description, uacs_code, unit_of_measure, unit_price, q1, q2, q3, q4, mode, group]
     * 'group' is PS-DBM / NON-PS / CO — matches the source doc's own funding-source
     * split, used only for the arithmetic cross-check below, not stored on the item.
     */
    private function items(): array
    {
        return [
            // --- PS-DBM, C1 Office Supplies Expenses (UACS 5020301000) ---
            ['BATTERY, dry Cell, size AA', '5020301000', 'Pack', 20.49, 50, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['BATTERY, dry Cell, size AAA', '5020301000', 'Pack', 18.34, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLEARBOOK, A4 size', '5020301000', 'PC', 35.16, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLEARBOOK, legal size', '5020301000', 'PC', 36.36, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLIP, backfold, 19mm', '5020301000', 'Box', 9.36, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLIP, backfold, 25mm', '5020301000', 'Box', 15.60, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLIP, backfold, 32mm', '5020301000', 'Box', 33.28, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['CLIP, backfold, 50mm', '5020301000', 'Box', 60.32, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['ERASER, FELT, for blackboard/whiteboard', '5020301000', 'PC', 14.48, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['FILE TAB DIVIDER, A4', '5020301000', 'Set', 11.13, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['FOLDER, L-type, A4', '5020301000', 'Pack', 200.28, 5, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['GLUE, all purpose', '5020301000', 'Bottle', 62.14, 5, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Permanent, Black', '5020301000', 'PC', 8.27, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Permanent, Blue', '5020301000', 'PC', 8.27, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Permanent, Red', '5020301000', 'PC', 8.27, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Whiteboard, Black', '5020301000', 'PC', 9.65, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Whiteboard, Blue', '5020301000', 'PC', 9.65, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MARKER, Whiteboard, Red', '5020301000', 'PC', 9.65, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER CLIP, vinyl/plastic coated, 33mm', '5020301000', 'Box', 8.82, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER CLIP, vinyl/plastic coated, jumbo, 50mm', '5020301000', 'Box', 19.46, 15, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER, Multi-Purpose, A4', '5020301000', 'Ream', 137.74, 25, 15, 10, 10, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER, Multi-Purpose, LEGAL', '5020301000', 'Ream', 158.91, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER, MULTICOPY, A4', '5020301000', 'Ream', 213.80, 50, 50, 50, 50, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PAPER, parchment', '5020301000', 'Box', 154.21, 20, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PENCIL, lead/graphite, with eraser', '5020301000', 'Box', 44.71, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['RECORD BOOK, 300 PAGES', '5020301000', 'Book', 91.70, 5, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['RECORD BOOK, 500 PAGES', '5020301000', 'Book', 124.56, 5, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Extra Fine Tip, Black', '5020301000', 'PC', 22.20, 0, 20, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Extra Fine Tip, Blue', '5020301000', 'PC', 22.20, 25, 10, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Extra Fine Tip, Red', '5020301000', 'PC', 22.20, 0, 10, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Fine Tip, Black', '5020301000', 'PC', 44.72, 50, 10, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Fine Tip, Blue', '5020301000', 'PC', 44.72, 50, 10, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Fine Tip, Red', '5020301000', 'PC', 45.76, 50, 10, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Medium Tip, Black', '5020301000', 'PC', 57.20, 50, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Medium Tip, Blue', '5020301000', 'PC', 57.20, 50, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SIGN PEN, Medium Tip, Red', '5020301000', 'PC', 57.20, 25, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['STAMP PAD, felt', '5020301000', 'PC', 39.92, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['STAPLE WIRE, standard', '5020301000', 'Box', 23.76, 30, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],

            // --- PS-DBM, D2 Semi Expendable Office Equipment (UACS 5020321002) ---
            ['MONOBLOC CHAIR, beige', '5020321002', 'PC', 341.12, 0, 100, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['MONOBLOC CHAIR, white', '5020321002', 'PC', 341.12, 0, 200, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['PENCIL SHARPENER', '5020321002', 'PC', 236.08, 5, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['SCISSORS, symmetrical/asymmetrical', '5020321002', 'Pair', 65.52, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],
            ['TAPE DISPENSER, table top', '5020321002', 'PC', 77.58, 10, 0, 0, 0, 'NP- AGENCY TO AGENCY', 'PS-DBM'],

            // --- NON-PS, A1/A2 Travelling Expense ---
            ['PLANE TICKET - LOCAL', '5020101000', 'Lot', 200000.00, 1, 0, 0, 0, 'DIRECT RETAIL PURCHASE OF AIRLINE TICKETS', 'NON-PS'],
            ['PLANE TICKET - FOREIGN', '5020102000', 'Lot', 250000.00, 1, 0, 0, 0, 'DIRECT RETAIL PURCHASE OF AIRLINE TICKETS', 'NON-PS'],

            // --- NON-PS, B1 Training Expenses ---
            ['CONDUCT OF TRAINING', '5020201000', 'Lot', 600000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['DECORATION (PLANTS, FLOWERS) FOR TRAINING/S', '5020201000', 'Lot', 75000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['MEALS AND SNACKS FOR TRAINING/S', '5020201000', 'Lot', 300000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['OTHER SERVICES FOR TRAINING/S', '5020201000', 'Lot', 20000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PROFESSIONAL SERVICES OF LECTURER FOR TRAINING/S', '5020201000', 'Lot', 30000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['RENTAL OF CHAIRS FOR TRAINING/S', '5020201000', 'Lot', 50000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['RENTAL OF VENUE FOR TRAINING/S', '5020201000', 'Lot', 50000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['SUPPLIES AND MATERIALS FOR TRAINING/S', '5020201000', 'Lot', 50000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['TRANSPORTATION EXPENSES (FUEL/VEHICLE HIRE) FOR TRAINING/S', '5020201000', 'Lot', 20000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, C1 Office Supplies Expenses ---
            ['Answer Sheet/Testing Materials', '5020301000', 'Lot', 200000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['ARCH FILE, 3-hole, A4, D-type, 1.5", with label (accreditation/ISO)', '5020301000', 'PC', 275.00, 20, 20, 0, 0, 'SHOPPING', 'NON-PS'],
            ['ARCH FILE, 3-hole, A4, D-type, 2", with label (accreditation/ISO)', '5020301000', 'PC', 320.00, 20, 20, 0, 0, 'SHOPPING', 'NON-PS'],
            ['Brochure Rack/Slanted Holder', '5020301000', 'PC', 5000.00, 0, 5, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['CERTIFICATE HOLDER, A4 SIZE, plastic with board', '5020301000', 'PC', 45.00, 20, 40, 0, 0, 'SHOPPING', 'NON-PS'],
            ['EPSON INK, BOTTLE, 003, black', '5020301000', 'Bottle', 335.00, 75, 20, 20, 20, 'SHOPPING', 'NON-PS'],
            ['EPSON INK, BOTTLE, 003, cyan', '5020301000', 'Bottle', 335.00, 50, 15, 15, 15, 'SHOPPING', 'NON-PS'],
            ['EPSON INK, BOTTLE, 003, magenta', '5020301000', 'Bottle', 335.00, 50, 15, 15, 15, 'SHOPPING', 'NON-PS'],
            ['EPSON INK, BOTTLE, 003, yellow', '5020301000', 'Bottle', 335.00, 50, 15, 15, 15, 'SHOPPING', 'NON-PS'],
            ['EPSON RIBBON FOR LX-310', '5020301000', 'PC', 135.00, 20, 10, 10, 10, 'SHOPPING', 'NON-PS'],
            ['FLOURESCENT MARKER, green', '5020301000', 'PC', 15.00, 15, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['FLOURESCENT MARKER, orange', '5020301000', 'PC', 15.00, 15, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['GLUE, multi purpose, 40g', '5020301000', 'Bottle', 25.00, 10, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['HP Ink Toner 119A (Set - CMYB)', '5020301000', 'Set', 30000.00, 1, 0, 1, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['INK REFILL, permanent marker, black', '5020301000', 'Bottle', 85.00, 0, 5, 0, 0, 'SHOPPING', 'NON-PS'],
            ['INK REFILL, white boardmarker, black', '5020301000', 'Bottle', 100.00, 0, 15, 0, 0, 'SHOPPING', 'NON-PS'],
            ['MARKING PEN, permanent, Fine, black', '5020301000', 'PC', 35.00, 25, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['MARKING PEN, permanent, Fine, blue', '5020301000', 'PC', 35.00, 10, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['MARKING PEN, permanent, Superfine, black', '5020301000', 'PC', 45.00, 10, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['Office Supplies (Accreditation)', '5020301000', 'Lot', 50000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Office Supplies (Foundation)', '5020301000', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Office Supplies (Graduation)', '5020301000', 'Lot', 25000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Office Supplies (Other Activities)', '5020301000', 'Lot', 50000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PENCIL, #2, 12\'s/bx', '5020301000', 'Box', 110.00, 5, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['PHOTO FRAME, with stand, A4 SIZE', '5020301000', 'PC', 150.00, 10, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['RJ45', '5020301000', 'PC', 5.00, 100, 0, 0, 0, 'SHOPPING', 'NON-PS'],
            ['SPECIALTY PAPER, 10\'s/pack, CREAM, A4', '5020301000', 'Pack', 35.00, 0, 10, 40, 0, 'SHOPPING', 'NON-PS'],
            ['SPECIALTY PAPER, 10\'s/pack, WHITE, A4', '5020301000', 'Pack', 35.00, 0, 20, 40, 0, 'SHOPPING', 'NON-PS'],
            ['STAMP PAD INK, black', '5020301000', 'Bottle', 25.00, 5, 0, 0, 0, 'SHOPPING', 'NON-PS'],

            // --- NON-PS, C10 Textbooks and Instructional Materials Expenses ---
            ['Other Instructional Materials', '5020311000', 'Lot', 250000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Textbook/Workbook', '5020311000', 'Lot', 300000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, C12 Other Supplies & Materials Expenses ---
            ['EXTENSION CORD, 4-universal plug, HD with individual switches', '5020399000', 'PC', 900.00, 6, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['EXTENSION CORD, 6-universal plug, 10 meters', '5020399000', 'Unit', 1100.00, 7, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['LED Bulb, 15 watts', '5020399000', 'PC', 280.00, 25, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (University Games)', '5020399000', 'Lot', 130000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (Student Awards)', '5020399000', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (Accreditation)', '5020399000', 'Lot', 40000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (Foundation)', '5020399000', 'Lot', 80000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (Culture & the Arts)', '5020399000', 'Lot', 60000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (Graduation)', '5020399000', 'Lot', 150000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Supplies and Materials (KMC/Research)', '5020399000', 'Lot', 51500.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Perculator 15L & Coffee Broiler', '5020399000', 'Unit', 10000.00, 0, 2, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Uniforms (University Games)', '5020399000', 'Lot', 300000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Various Kitchen Supplies and Materials', '5020399000', 'Lot', 250000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, C2 Accountable Forms / C3 Non Accountable Forms ---
            ['OFFICIAL RECEIPT', '5020302000', 'Booklet', 100.00, 50, 50, 50, 50, 'NP- AGENCY TO AGENCY', 'NON-PS'],
            ['REGISTRATION FORMS (2 ply carbonized, computer paper with logo), 8.5"x11"', '5020303000', 'Box', 3800.00, 20, 20, 20, 20, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['TOR BOARD sheeted with microtext and letterhead (REGISTRAR), 8.5"x13"', '5020303000', 'PC', 15.00, 10000, 0, 5000, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, C6/C7/C8 ---
            ['Various Drugs and Medicines', '5020307000', 'Lot', 50000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Various Medical, Dental, and Laboratory Supplies', '5020308000', 'Lot', 10000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Fuel, Oil, and Lubricants', '5020309000', 'Lot', 200000.00, 1, 0, 0, 0, 'DIRECT RETAIL PURCHASE OF POL PRODUCTS', 'NON-PS'],
            ['Fuel, Oil, and Lubricants (Accreditation)', '5020309000', 'Lot', 10000.00, 1, 0, 0, 0, 'DIRECT RETAIL PURCHASE OF POL PRODUCTS', 'NON-PS'],
            ['Fuel, Oil, and Lubricants (Graduation)', '5020309000', 'Lot', 10000.00, 1, 0, 0, 0, 'DIRECT RETAIL PURCHASE OF POL PRODUCTS', 'NON-PS'],

            // --- NON-PS, D11/D13 Semi Expendable Equipment ---
            ['Various Sports Equipment', '5020321012', 'Lot', 300000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['MICROPHONE wireless', '5020321099', 'Unit', 5500.00, 0, 4, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Sound System Portable', '5020321099', 'Unit', 35000.00, 0, 2, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Various Cleaning Machinery and Equipment', '5020321099', 'Lot', 77551.30, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Various Kitchen Equipment', '5020321099', 'Lot', 100000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Water Dispenser', '5020321099', 'Unit', 6000.00, 0, 5, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, D2 Semi Expendable Office Equipment ---
            ['Barcode Scanner', '5020321002', 'Unit', 2000.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Enclosed Bulletin Board 96x48 Lockable Cork Noticeboard', '5020321002', 'Unit', 25000.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Office Cabinet', '5020321002', 'Unit', 10000.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PAPER CUTTER, 10"x12"', '5020321002', 'Unit', 800.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PAPER CUTTER, 15"x18"', '5020321002', 'Unit', 1160.00, 3, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Paper Shredder HD', '5020321002', 'Unit', 10000.00, 8, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PUNCHER, 3-hole, HD', '5020321002', 'Unit', 1500.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['RULER, 12 inches, metal', '5020321002', 'PC', 40.00, 10, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['RULER, 24 inches, metal', '5020321002', 'PC', 100.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['SCISSOR, 6", HD', '5020321002', 'PC', 40.00, 15, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['STAMPING DATE, self-inking stamp, shiny', '5020321002', 'PC', 350.00, 15, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['STAPLER, HD, with remover, #35', '5020321002', 'PC', 200.00, 10, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['WALL CLOCK, quartz', '5020321002', 'PC', 400.00, 10, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, D3 Semi Expendable ICT Equipment ---
            ['2in1 Soldering Station (Digital Display SMD Hot Air Rework Station and Soldering Iron)', '5020321003', 'Set', 5000.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Object Camera with Clip Super Macro Focus', '5020321003', 'Unit', 10000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['PRINTER, Multifunction, CONTINUOUS INK', '5020321003', 'Unit', 12000.00, 5, 3, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Projector Portable', '5020321003', 'Unit', 35000.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Tablet', '5020321003', 'Unit', 10000.00, 10, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['UPS 650 watts', '5020321003', 'Unit', 3400.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['USB-Receiver Red Laser Pointer Wireless', '5020321003', 'Unit', 2000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Wireless HDMI Transmitter/Receiver', '5020321003', 'Unit', 3500.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Wireless Router', '5020321003', 'Unit', 8000.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, E1 Semi Expendables Furnitures and Fixtures ---
            ['BOOKSHELVES, Open Shelves, metal, 6 layers', '5020322001', 'Unit', 8450.00, 5, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['CABINET, Filing, four drawers, steel, plain', '5020322001', 'Unit', 10000.00, 10, 5, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Conference Table with Chairs (6-seaters) Laminated Wood Table', '5020322001', 'Unit', 35000.00, 2, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Foldable Magazine Brochure Stand', '5020322001', 'PC', 5000.00, 0, 2, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Foldable Table with Cover', '5020322001', 'Unit', 5000.00, 0, 20, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Monobloc Chair with Left-Handed Arm Rest', '5020322001', 'PC', 1250.00, 0, 80, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Monobloc Seat Cover', '5020322001', 'PC', 250.00, 0, 400, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Office Metal Cabinet with Glass Doors', '5020322001', 'Unit', 15000.00, 0, 8, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, G1/G2 Communication Expenses ---
            ['Postage and Deliveries', '5020501000', 'Lot', 12500.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Prepaid Cards/E-Load', '5020502001', 'Lot', 3600.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, J1/L1/L4/M3 Professional & General Services ---
            ['Research, Exploration, and Development', '5020702000', 'Lot', 200000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Legal Services', '5021101000', 'Lot', 60000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Other Professional Services', '5021199000', 'Lot', 272000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Security Services (Carmona)', '5021203000', 'Lot', 3319821.00, 1, 0, 0, 0, 'COMPETITIVE BIDDING', 'NON-PS'],

            // --- NON-PS, N Repair and Maintenance ---
            ['Repair of Sewage', '5021303003', 'Lot', 25000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Repair of Tiles/Windows/Doors', '5021304001', 'Lot', 50000.00, 1, 0, 1, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Repair of Laboratory/Classroom', '5021304002', 'Lot', 50000.00, 0, 1, 1, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Repair of Hostel', '5021304006', 'Lot', 50000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Repair of STAR Building', '5021304099', 'Lot', 75000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['R&M - Office Equipment', '5021305002', 'Lot', 25000.00, 0, 2, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['R&M - ICT Equipment', '5021305003', 'Lot', 25000.00, 0, 2, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['R&M - Motor Vehicles', '5021306001', 'Lot', 250000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P12 Other MOOE (Medals & Plaques) ---
            ['Medals & Medallion (Foundation)', '5029999099', 'Lot', 25000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Medals & Medallion (Graduation)', '5029999099', 'Lot', 15000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Medals & Medallion (Student Awards)', '5029999099', 'Lot', 30000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Plaque (Foundation)', '5029999099', 'Lot', 10000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Plaque (Graduation)', '5029999099', 'Lot', 5000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Plaque (Student Awards)', '5029999099', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P2 Printing and Binding ---
            ['Invitation/Program (Graduation)', '5029902000', 'Lot', 80000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Printing & Binding Expenses', '5029902000', 'Lot', 100000.00, 1, 1, 1, 1, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P3 Representation Expense ---
            ['Food/Catering (Accreditation)', '5029903000', 'Lot', 200000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (Culture & the Arts)', '5029903000', 'Lot', 42000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (Foundation)', '5029903000', 'Lot', 480000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (Graduation)', '5029903000', 'Lot', 400000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (KMC/Research)', '5029903000', 'Lot', 170500.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (Student Awards)', '5029903000', 'Lot', 150000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (Student Leaders\' Congress)', '5029903000', 'Lot', 150000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Food/Catering (University Games)', '5029903000', 'Lot', 750000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Representation Expenses', '5029903000', 'Lot', 309198.74, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P5 Rental of Venue ---
            ['Rental of Venue (Foundation)', '5029905001', 'Lot', 10000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Venue (Graduation)', '5029905001', 'Lot', 25000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Venue (KMC)', '5029905001', 'Lot', 3600.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P7 Rent Expense - Motor Vehicle ---
            ['Rental of Vehicle (Van Hire)', '5029905003', 'Lot', 15000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Vehicle (Van Hire) (KMC)', '5029905003', 'Lot', 5000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Vehicle (Van Hire/Foundation)', '5029905003', 'Lot', 10000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Vehicle (Van Hire/Graduation)', '5029905003', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Vehicle (Van Hire/University Games)', '5029905003', 'Lot', 100000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- NON-PS, P8 Rent Expense - Equipment ---
            ['Rental of LED-Wall (Foundation)', '5029905004', 'Lot', 60000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of LED-Wall (Graduation)', '5029905004', 'Lot', 35000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of LED-Wall (Student Awards)', '5029905004', 'Lot', 40000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of LED-Wall (Student Leaders\' Congress)', '5029905004', 'Lot', 25000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Sound System (Foundation)', '5029905004', 'Lot', 45000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Sound System (Graduation)', '5029905004', 'Lot', 30000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Sound System (Student Awards)', '5029905004', 'Lot', 30000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Sound System (Student Leaders\' Congress)', '5029905004', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Tables (Foundation)', '5029905004', 'Lot', 20000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Tables (Graduation)', '5029905004', 'Lot', 10000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Tables (Student Leaders\' Congress)', '5029905004', 'Lot', 5000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rental of Tent (Foundation)', '5029905004', 'Lot', 70000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rentals of Chairs (Foundation)', '5029905004', 'Lot', 30000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],
            ['Rentals of Chairs (Graduation)', '5029905004', 'Lot', 55000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'NON-PS'],

            // --- CO (Capital Outlay) ---
            ['TELEVISION, 55"', '1060599000', 'Unit', 50000.00, 0, 5, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'CO'],
            ['Laptop Special Purpose', '1060503000', 'Unit', 65000.00, 1, 0, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'CO'],
            ['Kubo (CSG)', '1060701000', 'Unit', 75000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'CO'],
            ['Office Divider (Research)', '1060701000', 'Lot', 226029.42, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'CO'],
            ['Office Divider (CSG)', '1060701000', 'Lot', 225000.00, 0, 1, 0, 0, 'NP- SMALL VALUE PROCUREMENT', 'CO'],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rochelle = User::where('username', 'rochelle')->first();
        $department = Department::where('department_code', 'OBO')->first();
        $category = Category::where('name', 'Others')->first();

        if (! $rochelle || ! $department || ! $category) {
            $this->command?->error('Missing prerequisite: rochelle user, OBO department, or Others category.');
            return;
        }

        $plan = ProcurementPlan::firstOrCreate(
            ['plan_number' => 'PPMP-2026-059 A'],
            [
                'year' => 2026,
                'department_id' => $department->id,
                'allocated_budget' => 16183947.01,
                'approved_budget' => 16183947.01,
                'remaining_budget' => 0,
                'status' => 'Approved',
                'prepared_by' => $rochelle->id,
                'approved_by' => $rochelle->id,
                'approved_at' => now(),
                'remarks' => 'Real BOR-approved FY2026 PPMP, Carmona Campus. Imported from source PDF — see project memory for transcription verification notes.',
            ]
        );

        if ($plan->items()->exists()) {
            $this->command?->info('PPMP-2026-059 A already has items — skipping re-import.');
            return;
        }

        $unitCache = [];
        $materialCache = [];
        $classificationCache = [];

        $runningTotals = ['PS-DBM' => 0.0, 'NON-PS' => 0.0, 'CO' => 0.0];

        foreach ($this->items() as [$description, $uacsCode, $um, $unitPrice, $q1, $q2, $q3, $q4, $mode, $group]) {

            if (! isset($unitCache[$um])) {
                $unitCache[$um] = Unit::firstOrCreate(['name' => $um])->id;
            }

            if (! array_key_exists($uacsCode, $classificationCache)) {
                $classificationCache[$uacsCode] = ProcurementClassification::where('uacs_code', $uacsCode)->value('id');
            }

            $materialKey = strtolower($description);

            if (! isset($materialCache[$materialKey])) {

                $material = Material::firstOrCreate(
                    ['name' => $description],
                    [
                        'unit_id' => $unitCache[$um],
                        'category_id' => $category->id,
                        'department_id' => $department->id,
                        'classification_id' => $classificationCache[$uacsCode],
                        'quantity' => 0,
                        'created_by' => $rochelle->id,
                    ]
                );

                $materialCache[$materialKey] = $material->id;

            }

            $annualQuantity = $q1 + $q2 + $q3 + $q4;
            $annualCost = round($annualQuantity * $unitPrice, 2);

            ProcurementPlanItem::create([
                'plan_id' => $plan->id,
                'material_id' => $materialCache[$materialKey],
                'material_name' => $description,
                'unit_id' => $unitCache[$um],
                'estimated_unit_cost' => $unitPrice,
                'q1' => $q1,
                'q2' => $q2,
                'q3' => $q3,
                'q4' => $q4,
                'annual_quantity' => $annualQuantity,
                'annual_cost' => $annualCost,
                'priority' => 'Medium',
                'procurement_method' => $mode,
                'source_of_fund' => '164',
                'is_approved' => true,
                'created_by' => $rochelle->id,
            ]);

            $runningTotals[$group] += $annualCost;

        }

        $plan->update([
            'remaining_budget' => $plan->allocated_budget - $plan->total_planned_cost,
        ]);

        $grandTotal = array_sum($runningTotals);

        $this->command?->info('PS-DBM total: ' . number_format($runningTotals['PS-DBM'], 2) . ' (expected 192,791.55)');
        $this->command?->info('NON-PS MOOE total: ' . number_format($runningTotals['NON-PS'], 2) . ' (expected 15,150,126.04)');
        $this->command?->info('CO total: ' . number_format($runningTotals['CO'], 2) . ' (expected 841,029.42)');
        $this->command?->info('GRAND total: ' . number_format($grandTotal, 2) . ' (expected 16,183,947.01)');
    }
}
