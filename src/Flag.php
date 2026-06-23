<?php

declare(strict_types=1);

namespace Reinder83\BinaryFlags;

/**
 * Optional enum representation of available binary flags.
 *
 * BIT_64 is intentionally excluded because it cannot be used reliably as a normal flag.
 */
enum Flag: int
{
    case Flag1 = Bits::BIT_1;
    case Flag2 = Bits::BIT_2;
    case Flag3 = Bits::BIT_3;
    case Flag4 = Bits::BIT_4;
    case Flag5 = Bits::BIT_5;
    case Flag6 = Bits::BIT_6;
    case Flag7 = Bits::BIT_7;
    case Flag8 = Bits::BIT_8;
    case Flag9 = Bits::BIT_9;
    case Flag10 = Bits::BIT_10;
    case Flag11 = Bits::BIT_11;
    case Flag12 = Bits::BIT_12;
    case Flag13 = Bits::BIT_13;
    case Flag14 = Bits::BIT_14;
    case Flag15 = Bits::BIT_15;
    case Flag16 = Bits::BIT_16;
    case Flag17 = Bits::BIT_17;
    case Flag18 = Bits::BIT_18;
    case Flag19 = Bits::BIT_19;
    case Flag20 = Bits::BIT_20;
    case Flag21 = Bits::BIT_21;
    case Flag22 = Bits::BIT_22;
    case Flag23 = Bits::BIT_23;
    case Flag24 = Bits::BIT_24;
    case Flag25 = Bits::BIT_25;
    case Flag26 = Bits::BIT_26;
    case Flag27 = Bits::BIT_27;
    case Flag28 = Bits::BIT_28;
    case Flag29 = Bits::BIT_29;
    case Flag30 = Bits::BIT_30;
    case Flag31 = Bits::BIT_31;
    case Flag32 = Bits::BIT_32;
    case Flag33 = Bits::BIT_33;
    case Flag34 = Bits::BIT_34;
    case Flag35 = Bits::BIT_35;
    case Flag36 = Bits::BIT_36;
    case Flag37 = Bits::BIT_37;
    case Flag38 = Bits::BIT_38;
    case Flag39 = Bits::BIT_39;
    case Flag40 = Bits::BIT_40;
    case Flag41 = Bits::BIT_41;
    case Flag42 = Bits::BIT_42;
    case Flag43 = Bits::BIT_43;
    case Flag44 = Bits::BIT_44;
    case Flag45 = Bits::BIT_45;
    case Flag46 = Bits::BIT_46;
    case Flag47 = Bits::BIT_47;
    case Flag48 = Bits::BIT_48;
    case Flag49 = Bits::BIT_49;
    case Flag50 = Bits::BIT_50;
    case Flag51 = Bits::BIT_51;
    case Flag52 = Bits::BIT_52;
    case Flag53 = Bits::BIT_53;
    case Flag54 = Bits::BIT_54;
    case Flag55 = Bits::BIT_55;
    case Flag56 = Bits::BIT_56;
    case Flag57 = Bits::BIT_57;
    case Flag58 = Bits::BIT_58;
    case Flag59 = Bits::BIT_59;
    case Flag60 = Bits::BIT_60;
    case Flag61 = Bits::BIT_61;
    case Flag62 = Bits::BIT_62;
    case Flag63 = Bits::BIT_63;

    /**
     * @return Mask<self>
     */
    public static function mask(self ...$flags): Mask
    {
        return Mask::fromFlags(...$flags);
    }
}
