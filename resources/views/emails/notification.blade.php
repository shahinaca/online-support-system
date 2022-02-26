

<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
    <title>RE: {{$enquiry->enquiry_code}}</title>
    <style>
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        font,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td {
            margin: 0;
            padding: 0;
            outline: 0;
            font-weight: inherit;
            font-style: inherit;
            font-size: 100%;
            font-family: inherit;
            vertical-align: baseline;
        }

        /* remember to define focus styles! */
        :focus {
            outline: 0;
        }

        body {
            line-height: 1;
            color: black;
            background: white;
        }

        ol,
        ul {
            list-style: none;
        }

        /* tables still need 'cellspacing="0"' in the markup */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        caption,
        th,
        td {
            text-align: left;
            font-weight: normal;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: "";
        }

        blockquote,
        q {
            quotes: """";
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; color: #707070; font-size: 17px; line-height: 24px; font-family: 'Raleway', sans-serif;">
<div class="wrapper" style="text-align: center; background: transparent; width: 100%">
    <center>
        <table cellpadding="0" style="width: 700px; min-width: 730px; border-collapse: collapse;" width="700px">
            <tbody>
            <tr>
                <td style="font-family: 'Raleway', sans-serif; text-align: center;" align="center">
                    <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;"
                           width="100%" align="left">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table table-header"
                                        style="width: 100%; border-collapse: collapse;" width="100%">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 30px;">
                                                <table class="table table-body-inner"
                                                    style="width: 100%; border-collapse: collapse;"
                                                    width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <td
                                                            style="font-size: 17px; line-height: 24px; padding-bottom: 15px; font-family: 'Raleway', sans-serif;">
                                                        Hi {{$enquiry->createdUser->full_name??''}},  <br>
                                                        Our team is answered to your question!! <br>
                                                        Ans:

                                                        {{$answer->reply}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td
                                                            style=" font-size: 17px; line-height: 24px;">
                                                            <table class="table table-protocols"
                                                                style="width: 100%; border-collapse: collapse;"
                                                                width="100%">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding: 10px; text-align: center; font-size: 17px; line-height: 24px; background: gray; color: white; font-weight: 500; font-family: 'Raleway', sans-serif;"
                                                                        align="center">More details - History</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-list"
                                                    style="width: 100%; border-collapse: collapse;"
                                                    width="100%">
                                                    <tbody>
                                                    <tr>
                                                        <th
                                                            style="width: 100px; padding-top: 23px; padding-bottom: 23px; border-bottom: 1px solid #707070;">
                                                            Question:
                                                        </th>
                                                        <td style="font-size: 17px; line-height: 21px; vertical-align: middle; padding-left: 20px; padding-top: 23px; padding-bottom: 23px; border-bottom: 1px solid #707070; font-family: 'Raleway', sans-serif;"
                                                            valign="middle">
                                                            {{$enquiry->question}}
                                                        </td>
                                                    </tr>
                                                        @foreach($enquiry->replies as $one)
                                                            <tr>
                                                                <th
                                                                    style="padding-top: 23px; padding-bottom: 23px; border-bottom: 1px solid #707070; vertical-align: top;">
                                                                    {{($one->user_type=='customer')? 'You': 'Team'}}
                                                                </th>
                                                                <td style="font-size: 17px; line-height: 21px; vertical-align: middle; padding-left: 20px; padding-top: 23px; padding-bottom: 23px; border-bottom: 1px solid #707070; font-family: 'Raleway', sans-serif;"
                                                                    valign="middle">
                                                                    {{$one->reply}}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </center>
</div>
</body>

</html>

