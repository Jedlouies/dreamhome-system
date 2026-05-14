--
-- PostgreSQL database dump
--

\restrict y94pQsSMURBm81DqaSW0aqqYYyXp4RdEcSGrY4NkpRAnYlPdLVxWgoehpYyOtYQ

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

-- Started on 2026-05-14 11:36:54 PST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 4091 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- TOC entry 252 (class 1255 OID 16385)
-- Name: get_all_payments(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_all_payments(p_leaseno text DEFAULT NULL::text) RETURNS TABLE(paymentid character varying, leaseno character varying, renterno character varying, renter_name text, propertyno character varying, property_address text, payment_date date, amount_paid numeric, payment_method text, running_balance numeric, notes text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        py.paymentid::VARCHAR,
        py.leaseno::VARCHAR,
        la.renterno::VARCHAR,
        (r.firstname || ' ' || r.lastname)::TEXT AS renter_name,
        la.propertyno::VARCHAR,
        (p.street || ', ' || p.city)::TEXT AS property_address,
        py.payment_date::DATE,
        py.amount_paid::NUMERIC,
        py.payment_method::TEXT,
        py.running_balance::NUMERIC,
        py.notes::TEXT
    FROM payment py
    JOIN lease_agreement la ON la.leaseno = py.leaseno
    JOIN renter r           ON r.renterno = la.renterno
    JOIN property p         ON p.propertyno = la.propertyno
    WHERE (p_leaseno IS NULL OR py.leaseno = p_leaseno)
    ORDER BY py.payment_date DESC, py.paymentid DESC;
END;
$$;


ALTER FUNCTION public.get_all_payments(p_leaseno text) OWNER TO postgres;

--
-- TOC entry 253 (class 1255 OID 16386)
-- Name: get_lease_history(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_lease_history(p_renterno text) RETURNS TABLE(leaseno character varying, property_address text, monthly_rent numeric, paymentmethod character varying, deposit numeric, isdepositpaid boolean, startdate date, enddate date, duration character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        l.leaseno::VARCHAR, 
        (p.street || ', ' || p.city)::TEXT as property_address, 
        l.monthly_rent::NUMERIC, 
        l.paymentmethod::VARCHAR, -- Explicitly cast to match the return type
        l.deposit::NUMERIC,
        l.isdepositpaid::BOOLEAN,
        l.startdate::DATE, 
        l.enddate::DATE,
        l.duration::VARCHAR
    FROM lease_agreement l
    JOIN property p ON l.propertyno = p.propertyno
    WHERE l.renterno = p_renterno
    ORDER BY l.startdate DESC;
END;
$$;


ALTER FUNCTION public.get_lease_history(p_renterno text) OWNER TO postgres;

--
-- TOC entry 265 (class 1255 OID 16387)
-- Name: get_properties_by_branch(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_properties_by_branch(p_branch_id text DEFAULT NULL::text, p_search text DEFAULT NULL::text) RETURNS TABLE(propertyno character varying, street text, area text, city text, postcode text, property_type text, no_of_rooms integer, monthly_rate numeric, staffno character varying, main_image text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        p.propertyno::VARCHAR,
        p.street::TEXT,
        p.area::TEXT,
        p.city::TEXT,
        p.postcode::TEXT,
        p.property_type::TEXT,
        p.no_of_rooms::INT,
        p.monthly_rate::NUMERIC,
        p.staffno::VARCHAR,
        p.main_image::TEXT
    FROM property p
    LEFT JOIN staff s ON s.staffno = p.staffno
    WHERE
        (p_branch_id IS NULL OR p.branchno = p_branch_id)
        AND
        (p_search IS NULL OR (
            p.street ILIKE '%' || p_search || '%' OR
            p.area ILIKE '%' || p_search || '%' OR
            p.city ILIKE '%' || p_search || '%' OR
            p.property_type ILIKE '%' || p_search || '%' OR
            p.staffno ILIKE '%' || p_search || '%'
        ));
END;
$$;


ALTER FUNCTION public.get_properties_by_branch(p_branch_id text, p_search text) OWNER TO postgres;

--
-- TOC entry 266 (class 1255 OID 16388)
-- Name: get_properties_report(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_properties_report(p_branchno text DEFAULT NULL::text) RETURNS TABLE(propertyno character varying, street text, area text, city text, property_type text, no_of_rooms integer, monthly_rate numeric, branchno character varying, branch_city text, staff_name text, lease_status text, current_renter text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        p.propertyno::VARCHAR,
        p.street::TEXT,
        p.area::TEXT,
        p.city::TEXT,
        p.property_type::TEXT,
        p.no_of_rooms::INTEGER,
        p.monthly_rate::NUMERIC,
        p.branchno::VARCHAR,
        b.city::TEXT AS branch_city,
        (s.firstname || ' ' || s.lastname)::TEXT AS staff_name,
        CASE
            WHEN la.leaseno IS NOT NULL AND la.enddate >= CURRENT_DATE THEN 'Leased'
            ELSE 'Available'
        END::TEXT AS lease_status,
        COALESCE(
            (r.firstname || ' ' || r.lastname), '-'
        )::TEXT AS current_renter
    FROM property p
    LEFT JOIN branch b          ON b.branchno = p.branchno
    LEFT JOIN staff s           ON s.staffno = p.staffno
    LEFT JOIN lease_agreement la ON la.propertyno = p.propertyno
                                AND la.enddate >= CURRENT_DATE
    LEFT JOIN renter r          ON r.renterno = la.renterno
    WHERE (p_branchno IS NULL OR p.branchno = p_branchno)
    ORDER BY p.branchno, p.propertyno;
END;
$$;


ALTER FUNCTION public.get_properties_report(p_branchno text) OWNER TO postgres;

--
-- TOC entry 267 (class 1255 OID 16389)
-- Name: get_property_details(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_property_details(p_propertyno text) RETURNS TABLE(propertyno character varying, street text, area text, city text, postcode text, property_type text, no_of_rooms integer, monthly_rate numeric, staff_name character varying, owner_name character varying, branch_info character varying, main_image text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        p.propertyno::VARCHAR, 
        p.street::TEXT, 
        p.area::TEXT, 
        p.city::TEXT, 
        p.postcode::TEXT, 
        p.property_type::TEXT, 
        p.no_of_rooms::INT, 
        p.monthly_rate::NUMERIC, 
        (s.firstname || ' ' || s.lastname)::VARCHAR staff_name,
        (o.firstname || ' ' || o.lastname)::VARCHAR owner_name,
        (b.street || ', ' || b.city)::VARCHAR AS branch_info,
        p.main_image::TEXT
    FROM property p
    LEFT JOIN staff s ON s.staffno = p.staffno
    LEFT JOIN branch b ON b.branchno = p.branchno
    LEFT JOIN property_owner o ON o.ownerid = p.ownerno
    WHERE p.propertyno = p_propertyno;
END;
$$;


ALTER FUNCTION public.get_property_details(p_propertyno text) OWNER TO postgres;

--
-- TOC entry 268 (class 1255 OID 16390)
-- Name: get_renter_viewings(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_renter_viewings(p_renterno text) RETURNS TABLE(viewingid character varying, title text, addr text, view_date date, comment text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        v.viewingid::VARCHAR, 
        p.street::TEXT as title, 
        (p.street || ', ' || p.city)::TEXT as addr, 
        v.view_date::DATE, 
        v.comment::TEXT
    FROM viewing v
    JOIN property p ON v.propertyno = p.propertyno
    WHERE v.renterno = p_renterno
    ORDER BY v.view_date DESC;
END;
$$;


ALTER FUNCTION public.get_renter_viewings(p_renterno text) OWNER TO postgres;

--
-- TOC entry 269 (class 1255 OID 16391)
-- Name: get_renters_by_branch(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_renters_by_branch(p_branchno text DEFAULT NULL::text, p_search text DEFAULT NULL::text) RETURNS TABLE(renterno character varying, firstname character varying, lastname character varying, address text, phone character varying, preferred_property_type character varying, max_rent numeric, comment text, branchno character varying, witness_staffno character varying, staff_name character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        r.renterno::VARCHAR,
        r.firstname::VARCHAR,
        r.lastname::VARCHAR,
        r.address::TEXT,
        r.phone::VARCHAR,
        r.preferred_property_type::VARCHAR,
        r.max_rent::NUMERIC,
        r.comment::TEXT,
        r.branchno::VARCHAR,
        r.witness_staffno::VARCHAR,
        (s.firstname || ' ' || s.lastname)::VARCHAR AS staff_name
    FROM renter r
    LEFT JOIN staff s ON s.staffno = r.witness_staffno
    WHERE 
        -- Filter by branch if p_branchno is provided
        (p_branchno IS NULL OR r.branchno = p_branchno)
        AND 
        -- Search by Name, Renter Number, or Address[cite: 8]
        (p_search IS NULL OR 
         r.firstname ILIKE '%' || p_search || '%' OR 
         r.lastname ILIKE '%' || p_search || '%' OR 
         r.renterno ILIKE '%' || p_search || '%' OR
         r.address ILIKE '%' || p_search || '%');
END;
$$;


ALTER FUNCTION public.get_renters_by_branch(p_branchno text, p_search text) OWNER TO postgres;

--
-- TOC entry 270 (class 1255 OID 16392)
-- Name: get_renters_details(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_renters_details(p_renterno text) RETURNS TABLE(renterno character varying, firstname character varying, lastname character varying, address text, phone character varying, sex character varying, preferred_property_type character varying, max_rent numeric, comment text, branchno character varying, witness_staffno character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        r.renterno::VARCHAR, 
        r.firstname::VARCHAR, 
        r.lastname::VARCHAR, 
        r.address::TEXT, 
        r.phone::VARCHAR, 
        r.sex::VARCHAR, 
        r.preferred_property_type ::VARCHAR, 
        r.max_rent ::NUMERIC, 
        r.comment::TEXT,
        r.branchno::VARCHAR, 
        r.witness_staffno::VARCHAR
    FROM renter r
    LEFT JOIN branch b ON r.branchno = b.branchno
    LEFT JOIN staff s ON r.witness_staffno = s.staffno
    WHERE r.renterno = p_renterno;
END;
$$;


ALTER FUNCTION public.get_renters_details(p_renterno text) OWNER TO postgres;

--
-- TOC entry 271 (class 1255 OID 16393)
-- Name: get_renters_report(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_renters_report(p_branchno text DEFAULT NULL::text) RETURNS TABLE(renterno character varying, renter_name text, phone character varying, branchno character varying, leaseno character varying, propertyno character varying, property_address text, monthly_rent numeric, startdate date, enddate date, total_paid numeric, balance numeric, payment_status character varying, is_overdue boolean)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        r.renterno::VARCHAR,
        (r.firstname || ' ' || r.lastname)::TEXT AS renter_name,
        r.phone::VARCHAR,
        r.branchno::VARCHAR,
        la.leaseno::VARCHAR,
        la.propertyno::VARCHAR,
        (p.street || ', ' || p.city)::TEXT AS property_address,
        la.monthly_rent::NUMERIC,
        la.startdate::DATE,
        la.enddate::DATE,
        COALESCE(la.total_paid, 0)::NUMERIC,
        COALESCE(la.balance, 0)::NUMERIC,
        COALESCE(la.payment_status, 'UNPAID')::VARCHAR,
        COALESCE(la.is_overdue, FALSE)::BOOLEAN
    FROM renter r
    LEFT JOIN lease_agreement la ON la.renterno = r.renterno
    LEFT JOIN property p         ON p.propertyno = la.propertyno
    WHERE (p_branchno IS NULL OR r.branchno = p_branchno)
    ORDER BY r.branchno, r.renterno;
END;
$$;


ALTER FUNCTION public.get_renters_report(p_branchno text) OWNER TO postgres;

--
-- TOC entry 272 (class 1255 OID 16394)
-- Name: get_revenue_report(text, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_revenue_report(p_branchno text DEFAULT NULL::text, p_year integer DEFAULT NULL::integer) RETURNS TABLE(branchno character varying, branch_city text, report_month text, report_year integer, total_collected numeric, total_outstanding numeric, active_leases bigint, paid_leases bigint, overdue_leases bigint)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        p.branchno::VARCHAR,
        b.city::TEXT AS branch_city,
        TO_CHAR(py.payment_date, 'Month')::TEXT AS report_month,
        EXTRACT(YEAR FROM py.payment_date)::INTEGER AS report_year,
        SUM(py.amount_paid)::NUMERIC AS total_collected,
        SUM(la.balance)::NUMERIC AS total_outstanding,
        COUNT(DISTINCT la.leaseno) AS active_leases,
        COUNT(DISTINCT CASE WHEN la.payment_status = 'PAID' THEN la.leaseno END) AS paid_leases,
        COUNT(DISTINCT CASE WHEN la.is_overdue = TRUE THEN la.leaseno END) AS overdue_leases
    FROM payment py
    JOIN lease_agreement la ON la.leaseno = py.leaseno
    JOIN property p         ON p.propertyno = la.propertyno
    JOIN branch b           ON b.branchno = p.branchno
    WHERE
        (p_branchno IS NULL OR p.branchno = p_branchno)
        AND (p_year IS NULL OR EXTRACT(YEAR FROM py.payment_date) = p_year)
    GROUP BY
        p.branchno, b.city,
        TO_CHAR(py.payment_date, 'Month'),
        EXTRACT(YEAR FROM py.payment_date),
        EXTRACT(MONTH FROM py.payment_date)
    ORDER BY
        p.branchno,
        report_year DESC,
        EXTRACT(MONTH FROM py.payment_date) DESC;
END;
$$;


ALTER FUNCTION public.get_revenue_report(p_branchno text, p_year integer) OWNER TO postgres;

--
-- TOC entry 273 (class 1255 OID 16395)
-- Name: get_staff_by_branch(text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_staff_by_branch(p_branch_id text DEFAULT NULL::text, p_search text DEFAULT NULL::text) RETURNS TABLE(staffno character varying, firstname character varying, lastname character varying, email character varying, "position" character varying, nin character varying, address text, date_joined date)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        s.staffno::VARCHAR, 
        s.firstname::VARCHAR, 
        s.lastname::VARCHAR, 
        s.email::VARCHAR, 
        s.position::VARCHAR, 
        s.nin::VARCHAR, 
        s.address::TEXT, 
        s.date_joined::DATE
    FROM staff s
    WHERE 
        -- Branch Filter
        (p_branch_id IS NULL OR s.branchno = p_branch_id)
        AND 
        -- Search Filter (Firstname, Lastname, or Staff Number)
        (p_search IS NULL OR (
            s.firstname ILIKE '%' || p_search || '%' OR 
            s.lastname ILIKE '%' || p_search || '%' OR 
            s.staffno ILIKE '%' || p_search || '%'
        ));
END;
$$;


ALTER FUNCTION public.get_staff_by_branch(p_branch_id text, p_search text) OWNER TO postgres;

--
-- TOC entry 274 (class 1255 OID 16396)
-- Name: get_staff_details(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_staff_details(p_staffno text) RETURNS TABLE(staffno character varying, firstname character varying, lastname character varying, address text, telephoneno character varying, sex character varying, date_of_birth date, nin character varying, "position" character varying, salary numeric, branchno character varying, email character varying, branch_city character varying, date_joined date)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT 
        s.staffno::VARCHAR, 
        s.firstname::VARCHAR, 
        s.lastname::VARCHAR, 
        s.address::TEXT, 
        s.telephoneno::VARCHAR, 
        s.sex::VARCHAR, 
        s.date_of_birth::DATE, 
        s.nin::VARCHAR, 
        s.position::VARCHAR, 
        s.salary::NUMERIC, 
        s.branchno::VARCHAR, 
        s.email::VARCHAR,
        b.city::VARCHAR as branch_city,
        s.date_joined::DATE -- Ensure this column exists in your 'staff' table!
    FROM staff s
    LEFT JOIN branch b ON s.branchno = b.branchno
    WHERE s.staffno = p_staffno;
END;
$$;


ALTER FUNCTION public.get_staff_details(p_staffno text) OWNER TO postgres;

--
-- TOC entry 275 (class 1255 OID 16397)
-- Name: get_staff_performance_report(text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_staff_performance_report(p_branchno text DEFAULT NULL::text) RETURNS TABLE(staffno character varying, staff_name text, staff_position character varying, branchno character varying, branch_city text, properties_managed bigint, leases_arranged bigint, total_revenue numeric, active_leases bigint, overdue_leases bigint)
    LANGUAGE plpgsql
    AS $$
BEGIN
    RETURN QUERY
    SELECT
        s.staffno::VARCHAR,
        (s.firstname || ' ' || s.lastname)::TEXT AS staff_name,
        s."position"::VARCHAR AS staff_position,
        s.branchno::VARCHAR,
        b.city::TEXT AS branch_city,
        COUNT(DISTINCT p.propertyno) AS properties_managed,
        COUNT(DISTINCT la.leaseno) AS leases_arranged,
        COALESCE(SUM(la.total_paid), 0)::NUMERIC AS total_revenue,
        COUNT(DISTINCT CASE WHEN la.enddate >= CURRENT_DATE THEN la.leaseno END) AS active_leases,
        COUNT(DISTINCT CASE WHEN la.is_overdue = TRUE THEN la.leaseno END) AS overdue_leases
    FROM staff s
    LEFT JOIN branch b           ON b.branchno = s.branchno
    LEFT JOIN property p         ON p.staffno = s.staffno
    LEFT JOIN lease_agreement la ON la.staffno = s.staffno
    WHERE (p_branchno IS NULL OR s.branchno = p_branchno)
    GROUP BY s.staffno, s.firstname, s.lastname, s."position", s.branchno, b.city
    ORDER BY leases_arranged DESC, properties_managed DESC;
END;
$$;


ALTER FUNCTION public.get_staff_performance_report(p_branchno text) OWNER TO postgres;

--
-- TOC entry 276 (class 1255 OID 16398)
-- Name: insert_payment(text, text, numeric, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insert_payment(IN p_paymentid text, IN p_leaseno text, IN p_amount_paid numeric, IN p_payment_method text, IN p_notes text DEFAULT NULL::text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO payment (
        paymentid,
        leaseno,
        payment_date,
        amount_paid,
        payment_method,
        notes
    ) VALUES (
        p_paymentid,
        p_leaseno,
        CURRENT_DATE,
        p_amount_paid,
        p_payment_method,
        p_notes
    );
END;
$$;


ALTER PROCEDURE public.insert_payment(IN p_paymentid text, IN p_leaseno text, IN p_amount_paid numeric, IN p_payment_method text, IN p_notes text) OWNER TO postgres;

--
-- TOC entry 277 (class 1255 OID 16399)
-- Name: insert_property(text, text, text, text, text, text, integer, numeric, text, text, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insert_property(IN p_propertyno text, IN p_street text, IN p_area text, IN p_city text, IN p_postcode text, IN p_property_type text, IN p_no_of_rooms integer, IN p_monthly_rate numeric, IN p_staffno text, IN p_ownerno text, IN p_branchno text, IN p_main_image text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO property (
        propertyno, street, area, city, postcode, 
        property_type, no_of_rooms, monthly_rate, 
        staffno, ownerno, branchno, main_image
    ) VALUES (
        p_propertyno, p_street, p_area, p_city, p_postcode, 
        p_property_type, p_no_of_rooms, p_monthly_rate, 
        p_staffno, p_ownerno, p_branchno, p_main_image
    );
END;
$$;


ALTER PROCEDURE public.insert_property(IN p_propertyno text, IN p_street text, IN p_area text, IN p_city text, IN p_postcode text, IN p_property_type text, IN p_no_of_rooms integer, IN p_monthly_rate numeric, IN p_staffno text, IN p_ownerno text, IN p_branchno text, IN p_main_image text) OWNER TO postgres;

--
-- TOC entry 278 (class 1255 OID 16400)
-- Name: insert_renter(text, text, text, text, text, text, numeric, text, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insert_renter(IN p_renterno text, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_phone text, IN p_preferred_type text, IN p_max_rent numeric, IN p_comment text, IN p_branchno text, IN p_witness_staffno text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO renter (
        renterno, firstname, lastname, address, phone, 
        preferred_property_type, max_rent, comment, 
        branchno, witness_staffno
    ) VALUES (
        p_renterno, p_firstname, p_lastname, p_address, p_phone, 
        p_preferred_type, p_max_rent, p_comment, 
        p_branchno, p_witness_staffno
    );
END;
$$;


ALTER PROCEDURE public.insert_renter(IN p_renterno text, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_phone text, IN p_preferred_type text, IN p_max_rent numeric, IN p_comment text, IN p_branchno text, IN p_witness_staffno text) OWNER TO postgres;

--
-- TOC entry 279 (class 1255 OID 16401)
-- Name: insert_staff_member(character varying, text, text, text, text, text, date, text, text, numeric, character varying, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insert_staff_member(IN p_staffno character varying, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_telephoneno text, IN p_sex text, IN p_dob date, IN p_nin text, IN p_position text, IN p_salary numeric, IN p_branchno character varying, IN p_password text, IN p_email text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO staff (
        staffno, 
        firstname, 
        lastname, 
        address, 
        telephoneno, 
        sex, 
        date_of_birth, 
        nin, 
        position, 
        salary, 
        date_joined, 
        branchno, 
        password, 
        email
    ) VALUES (
        p_staffno, 
        p_firstname, 
        p_lastname, 
        p_address, 
        p_telephoneno, 
        p_sex, 
        p_dob, 
        p_nin, 
        p_position, 
        p_salary, 
        CURRENT_DATE, -- Automatically sets the joined date
        p_branchno, 
        p_password, 
        p_email
    );
END;
$$;


ALTER PROCEDURE public.insert_staff_member(IN p_staffno character varying, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_telephoneno text, IN p_sex text, IN p_dob date, IN p_nin text, IN p_position text, IN p_salary numeric, IN p_branchno character varying, IN p_password text, IN p_email text) OWNER TO postgres;

--
-- TOC entry 280 (class 1255 OID 16402)
-- Name: insert_viewing(character varying, character varying, character varying, date, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.insert_viewing(IN p_viewingid character varying, IN p_propertyno character varying, IN p_renterno character varying, IN p_view_date date, IN p_comment text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO viewing (viewingid, propertyno, renterno, view_date, comment)
    VALUES (p_viewingid, p_propertyno, p_renterno, p_view_date, p_comment);
END;
$$;


ALTER PROCEDURE public.insert_viewing(IN p_viewingid character varying, IN p_propertyno character varying, IN p_renterno character varying, IN p_view_date date, IN p_comment text) OWNER TO postgres;

--
-- TOC entry 281 (class 1255 OID 16403)
-- Name: trg_flag_overdue_lease(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trg_flag_overdue_lease() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
 IF (NEW.enddate < CURRENT_DATE) OR (NEW.isdepositpaid = FALSE) THEN
 NEW.is_overdue := TRUE;
 ELSE
 NEW.is_overdue := FALSE;
 END IF;
 RETURN NEW;
END;
$$;


ALTER FUNCTION public.trg_flag_overdue_lease() OWNER TO postgres;

--
-- TOC entry 282 (class 1255 OID 16404)
-- Name: trg_update_lease_on_payment(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.trg_update_lease_on_payment() RETURNS trigger
    LANGUAGE plpgsql
    AS $$ 
DECLARE 
 v_monthly_rent NUMERIC;
 v_duration INTEGER; 
 v_total_rent NUMERIC; 
 v_total_paid NUMERIC; 
 v_new_balance NUMERIC;
BEGIN
 SELECT 
 monthly_rent, 
 duration, 
 COALESCE(total_paid, 0) 
INTO 
 v_monthly_rent, 
 v_duration, 
 v_total_paid 
FROM lease_agreement
WHERE leaseno = NEW.leaseno;
IF NOT FOUND THEN 
 RAISE EXCEPTION 'Lease % does not exist.', NEW.leaseno;
END IF;
v_total_rent := v_monthly_rent * v_duration; 
v_total_paid := v_total_paid + NEW.amount_paid; 
v_new_balance := v_total_rent - v_total_paid;
NEW.running_balance := v_new_balance;
UPDATE lease_agreement 
SET total_paid = v_total_paid, 
  balance = v_new_balance, 
  payment_status = CASE
	WHEN v_new_balance <= 0 THEN 'PAID' 
	WHEN v_total_paid > 0 THEN 'PARTIAL' 
	ELSE 'UNPAID' 
	END 
WHERE leaseno = NEW.leaseno; 
RETURN NEW; 
END; 
$$;


ALTER FUNCTION public.trg_update_lease_on_payment() OWNER TO postgres;

--
-- TOC entry 249 (class 1255 OID 16405)
-- Name: update_property(text, text, text, text, text, text, integer, numeric, text, text, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.update_property(IN p_propertyno text, IN p_street text, IN p_area text, IN p_city text, IN p_postcode text, IN p_property_type text, IN p_no_of_rooms integer, IN p_monthly_rate numeric, IN p_staffno text, IN p_ownerno text, IN p_branchno text, IN p_main_image text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE property
    SET 
        street = p_street,
        area = p_area,
        city = p_city,
        postcode = p_postcode,
        property_type = p_property_type,
        no_of_rooms = p_no_of_rooms,
        monthly_rate = p_monthly_rate,
        staffno = p_staffno,
        ownerno = p_ownerno,
        branchno = p_branchno,
        main_image = p_main_image
    WHERE propertyno = p_propertyno;
END;
$$;


ALTER PROCEDURE public.update_property(IN p_propertyno text, IN p_street text, IN p_area text, IN p_city text, IN p_postcode text, IN p_property_type text, IN p_no_of_rooms integer, IN p_monthly_rate numeric, IN p_staffno text, IN p_ownerno text, IN p_branchno text, IN p_main_image text) OWNER TO postgres;

--
-- TOC entry 250 (class 1255 OID 16406)
-- Name: update_renter(character varying, character varying, character varying, character varying, character varying, character varying, character varying, numeric, text, character varying, character varying); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.update_renter(IN p_renterno character varying, IN p_firstname character varying, IN p_lastname character varying, IN p_address character varying, IN p_phone character varying, IN p_sex character varying, IN p_preferred_property_type character varying, IN p_max_rent numeric, IN p_comment text, IN p_branchno character varying, IN p_witness_staffno character varying)
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE renter
    SET
        firstname = p_firstname,
        lastname = p_lastname, 
        address = p_address,
        phone = p_phone,
        sex = p_sex,
        preferred_property_type = p_preferred_property_type,
        max_rent = p_max_rent,
        comment = p_comment,
        branchno = p_branchno,
        witness_staffno = p_witness_staffno
    WHERE renterno = p_renterno;
END;
$$;


ALTER PROCEDURE public.update_renter(IN p_renterno character varying, IN p_firstname character varying, IN p_lastname character varying, IN p_address character varying, IN p_phone character varying, IN p_sex character varying, IN p_preferred_property_type character varying, IN p_max_rent numeric, IN p_comment text, IN p_branchno character varying, IN p_witness_staffno character varying) OWNER TO postgres;

--
-- TOC entry 251 (class 1255 OID 16407)
-- Name: update_staff_member(text, text, text, text, text, text, date, text, text, numeric, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.update_staff_member(IN p_staffno text, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_telephoneno text, IN p_sex text, IN p_date_of_birth date, IN p_nin text, IN p_position text, IN p_salary numeric, IN p_branchno text, IN p_email text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE staff
    SET 
        firstname = p_firstname,
        lastname = p_lastname,
        address = p_address,
        telephoneno = p_telephoneno,
        sex = p_sex,
        date_of_birth = p_date_of_birth,
        nin = p_nin,
        position = p_position,
        salary = p_salary,
        branchno = p_branchno,
        email = p_email
    WHERE staffno = p_staffno;
END;
$$;


ALTER PROCEDURE public.update_staff_member(IN p_staffno text, IN p_firstname text, IN p_lastname text, IN p_address text, IN p_telephoneno text, IN p_sex text, IN p_date_of_birth date, IN p_nin text, IN p_position text, IN p_salary numeric, IN p_branchno text, IN p_email text) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 219 (class 1259 OID 16408)
-- Name: advertisement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.advertisement (
    advertid character varying(20) NOT NULL,
    propertyno character varying(20),
    newspaperid character varying(20),
    date_advertised date
);


ALTER TABLE public.advertisement OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16412)
-- Name: branch; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.branch (
    branchno character varying(20) NOT NULL,
    street text,
    area text,
    city text,
    postcode text,
    phone text,
    faxno text
);


ALTER TABLE public.branch OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16418)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16426)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16434)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16447)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 4092 (class 0 OID 0)
-- Dependencies: 224
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 225 (class 1259 OID 16448)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16460)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16471)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 4093 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 228 (class 1259 OID 16472)
-- Name: lease_agreement; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lease_agreement (
    leaseno character varying(20) NOT NULL,
    propertyno character varying(20),
    renterno character varying(20),
    staffno character varying(20),
    monthly_rent numeric,
    paymentmethod text,
    deposit numeric,
    isdepositpaid boolean,
    startdate date,
    enddate date,
    duration integer,
    total_paid numeric DEFAULT 0,
    balance numeric DEFAULT 0,
    payment_status text DEFAULT 'UNPAID'::text,
    is_overdue boolean DEFAULT false
);


ALTER TABLE public.lease_agreement OWNER TO postgres;

--
-- TOC entry 229 (class 1259 OID 16482)
-- Name: manager; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.manager (
    staffno character varying(20) NOT NULL,
    start_date date,
    bonus numeric,
    car_allowance numeric
);


ALTER TABLE public.manager OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16488)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 16494)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 4094 (class 0 OID 0)
-- Dependencies: 231
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 232 (class 1259 OID 16495)
-- Name: newspaper; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.newspaper (
    newspaperid character varying(20) NOT NULL,
    name text
);


ALTER TABLE public.newspaper OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16501)
-- Name: next_of_kin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.next_of_kin (
    kinid character varying(20) NOT NULL,
    staffno character varying(20),
    fullname text,
    relationship text,
    address text,
    phone text
);


ALTER TABLE public.next_of_kin OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16507)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16514)
-- Name: payment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payment (
    paymentid character varying NOT NULL,
    leaseno character varying,
    payment_date date DEFAULT CURRENT_DATE,
    amount_paid numeric NOT NULL,
    payment_method text,
    running_balance numeric,
    notes text
);


ALTER TABLE public.payment OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 16806)
-- Name: payment_submission; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.payment_submission (
    submissionid character varying NOT NULL,
    leaseno character varying,
    renterno character varying,
    amount numeric NOT NULL,
    payment_method text NOT NULL,
    reference_no character varying(100) NOT NULL,
    payment_date date NOT NULL,
    notes text,
    status character varying DEFAULT 'Pending'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.payment_submission OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 16522)
-- Name: property; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.property (
    propertyno character varying(20) NOT NULL,
    street text,
    area text,
    city text,
    postcode text,
    property_type text,
    no_of_rooms smallint,
    monthly_rate numeric,
    staffno character varying(20),
    ownerno character varying(20),
    branchno character varying(20),
    main_image text
);


ALTER TABLE public.property OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 16528)
-- Name: property_inspection; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.property_inspection (
    inspectionid character varying(20) NOT NULL,
    propertyno character varying(20),
    staffno character varying(20),
    inspection_date date,
    evaluation text
);


ALTER TABLE public.property_inspection OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 16534)
-- Name: property_owner; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.property_owner (
    ownerid character varying(20) NOT NULL,
    firstname text,
    lastname text,
    address text,
    contact text
);


ALTER TABLE public.property_owner OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 16540)
-- Name: renewal_request; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.renewal_request (
    requestid character varying DEFAULT ('RR'::text || (EXTRACT(epoch FROM now()))::bigint) NOT NULL,
    leaseno character varying,
    renterno character varying,
    reason character varying NOT NULL,
    message text,
    status character varying DEFAULT 'Pending'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.renewal_request OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 16550)
-- Name: renter; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.renter (
    renterno character varying(20) NOT NULL,
    firstname text,
    lastname text,
    address text,
    phone text,
    preferred_property_type text,
    max_rent numeric,
    comment text,
    branchno character varying(20),
    witness_staffno character varying(20)
);


ALTER TABLE public.renter OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 16556)
-- Name: secretary; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.secretary (
    staffno character varying(20) NOT NULL,
    typing_speed numeric
);


ALTER TABLE public.secretary OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 16562)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id character varying(50),
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 16570)
-- Name: staff; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.staff (
    staffno character varying(20) NOT NULL,
    firstname text,
    lastname text,
    address text,
    telephoneno text,
    sex text,
    date_of_birth date,
    nin text,
    "position" text,
    salary numeric,
    date_joined date DEFAULT CURRENT_DATE,
    branchno character varying(20),
    password text,
    email text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.staff OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 16577)
-- Name: support_ticket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.support_ticket (
    ticketid character varying DEFAULT ('ST'::text || (EXTRACT(epoch FROM now()))::bigint) NOT NULL,
    renterno character varying,
    leaseno character varying,
    issue_type character varying NOT NULL,
    message text,
    status character varying DEFAULT 'Open'::character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.support_ticket OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 16587)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    renterno character varying
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 16596)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 4095 (class 0 OID 0)
-- Dependencies: 246
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 247 (class 1259 OID 16597)
-- Name: viewing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.viewing (
    viewingid character varying(20) NOT NULL,
    propertyno character varying(20),
    renterno character varying(20),
    view_date date,
    comment text
);


ALTER TABLE public.viewing OWNER TO postgres;

--
-- TOC entry 3796 (class 2604 OID 16603)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 3798 (class 2604 OID 16604)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 3803 (class 2604 OID 16605)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 3812 (class 2604 OID 16606)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4056 (class 0 OID 16408)
-- Dependencies: 219
-- Data for Name: advertisement; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.advertisement (advertid, propertyno, newspaperid, date_advertised) FROM stdin;
AD501	PC4	N401	2026-04-10
\.


--
-- TOC entry 4057 (class 0 OID 16412)
-- Dependencies: 220
-- Data for Name: branch; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.branch (branchno, street, area, city, postcode, phone, faxno) FROM stdin;
B001	123 Main St	Downtown	Cagayan de Oro	9000	0917-123-4567	881-1234
B002	456 Uptown Rd	Balulang	Cagayan de Oro	9000	0917-987-6543	881-5678
\.


--
-- TOC entry 4058 (class 0 OID 16418)
-- Dependencies: 221
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-tim@example.com|127.0.0.1:timer	i:1778444469;	1778444469
laravel-cache-tim@example.com|127.0.0.1	i:1;	1778444469
\.


--
-- TOC entry 4059 (class 0 OID 16426)
-- Dependencies: 222
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 4060 (class 0 OID 16434)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 4062 (class 0 OID 16448)
-- Dependencies: 225
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 4063 (class 0 OID 16460)
-- Dependencies: 226
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 4065 (class 0 OID 16472)
-- Dependencies: 228
-- Data for Name: lease_agreement; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lease_agreement (leaseno, propertyno, renterno, staffno, monthly_rent, paymentmethod, deposit, isdepositpaid, startdate, enddate, duration, total_paid, balance, payment_status, is_overdue) FROM stdin;
L301	PC4	R201	S001	20000	Cash	30000	t	2026-01-01	2026-12-31	12	20000	220000	PARTIAL	f
\.


--
-- TOC entry 4066 (class 0 OID 16482)
-- Dependencies: 229
-- Data for Name: manager; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.manager (staffno, start_date, bonus, car_allowance) FROM stdin;
S001	2025-01-10	5000	2000
\.


--
-- TOC entry 4067 (class 0 OID 16488)
-- Dependencies: 230
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_04_20_133644_create_dashboards_table	2
\.


--
-- TOC entry 4069 (class 0 OID 16495)
-- Dependencies: 232
-- Data for Name: newspaper; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.newspaper (newspaperid, name) FROM stdin;
N401	SunStar CDO
N402	Gold Star Daily
\.


--
-- TOC entry 4070 (class 0 OID 16501)
-- Dependencies: 233
-- Data for Name: next_of_kin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.next_of_kin (kinid, staffno, fullname, relationship, address, phone) FROM stdin;
K601	S001	Maria Camara	Mother	Lapasan, CDO	0911-222-3333
\.


--
-- TOC entry 4071 (class 0 OID 16507)
-- Dependencies: 234
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 4072 (class 0 OID 16514)
-- Dependencies: 235
-- Data for Name: payment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payment (paymentid, leaseno, payment_date, amount_paid, payment_method, running_balance, notes) FROM stdin;
PAY6A0511787E0B6	L301	2026-05-14	20000	GCash	220000	January Rent | Ref: 1223456789
\.


--
-- TOC entry 4085 (class 0 OID 16806)
-- Dependencies: 248
-- Data for Name: payment_submission; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.payment_submission (submissionid, leaseno, renterno, amount, payment_method, reference_no, payment_date, notes, status, created_at) FROM stdin;
\.


--
-- TOC entry 4073 (class 0 OID 16522)
-- Dependencies: 236
-- Data for Name: property; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.property (propertyno, street, area, city, postcode, property_type, no_of_rooms, monthly_rate, staffno, ownerno, branchno, main_image) FROM stdin;
PC4	6 Lawrence St.	Patag	Cagayan de Oro City	9000	Flat	3	15000	S001	O101	B001	flat3.jpg
PC21	18 Dale Road	Uptown	Cagayan de Oro City	9000	House	5	25000	S001	O101	B001	tierra.jpg
PC36	2 Manor Road	Carmen	Cagayan de Oro City	9000	Flat	3	12500	S002	O102	B002	flat2.jpg
PC16	5 Novar Drive	Balulang	Cagayan de Oro City	9000	Flat	4	18000	S002	O102	B002	flat1.jpg
\.


--
-- TOC entry 4074 (class 0 OID 16528)
-- Dependencies: 237
-- Data for Name: property_inspection; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.property_inspection (inspectionid, propertyno, staffno, inspection_date, evaluation) FROM stdin;
\.


--
-- TOC entry 4075 (class 0 OID 16534)
-- Dependencies: 238
-- Data for Name: property_owner; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.property_owner (ownerid, firstname, lastname, address, contact) FROM stdin;
O101	Robert	Fox	Nazareth, CDO	0912-345-6789
O102	Mary	Jane	Macasandig, CDO	0915-000-1111
\.


--
-- TOC entry 4076 (class 0 OID 16540)
-- Dependencies: 239
-- Data for Name: renewal_request; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.renewal_request (requestid, leaseno, renterno, reason, message, status, created_at) FROM stdin;
RR1778499414	L301	R201	Extension Needed	\N	Pending	2026-05-11 11:36:54
\.


--
-- TOC entry 4077 (class 0 OID 16550)
-- Dependencies: 240
-- Data for Name: renter; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.renter (renterno, firstname, lastname, address, phone, preferred_property_type, max_rent, comment, branchno, witness_staffno) FROM stdin;
R201	John	Doe	Bulua, CDO	0922-777-8888	House	20000	Needs parking	B001	S001
R202	Jane	Smith	Iponan, CDO	0922-999-0000	Flat	10000	Near university	B002	S002
\.


--
-- TOC entry 4078 (class 0 OID 16556)
-- Dependencies: 241
-- Data for Name: secretary; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.secretary (staffno, typing_speed) FROM stdin;
S002	75.5
\.


--
-- TOC entry 4079 (class 0 OID 16562)
-- Dependencies: 242
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
TLnuoAJDSLREyZIyCRjsyBAfFuenYBUdfremBVTv	1	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	YTo1OntzOjY6Il90b2tlbiI7czo0MDoib0hxWDYzU05mNkZGcDVPcFU5UVR4ZWtHcmVqUTdiNUlucklJRlFzaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9	1778722648
lPTw0ugHgajW61JwH7OmkgbMe7iY6ZfnGnSFAbCW	1	127.0.0.1	Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.119.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWjFVd0pxanZEZ01LZFdtUG52a0NnOEF2UkNqdTE0Z3NIRVRROUtWVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=	1778677673
\.


--
-- TOC entry 4080 (class 0 OID 16570)
-- Dependencies: 243
-- Data for Name: staff; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.staff (staffno, firstname, lastname, address, telephoneno, sex, date_of_birth, nin, "position", salary, date_joined, branchno, password, email, created_at, updated_at) FROM stdin;
S002	Alice	Guo	Puerto, CDO	0955-333-4444	F	1998-08-20	NIN456	Assistant	25000	2025-02-15	B001	$2y$12$QngyoLEV4p3E7vlqGHncne6Tr9uYP9VYleOzcB1Erz9eOYGkrScGu	alice@example.com	\N	2026-04-21 08:21:34
S001	Jed Louies	Camara	Lapasan, CDO	0955-111-2222	M	2004-05-15	NIN123	Manager	45000	2025-01-10	B001	$2y$12$dtTcfeQqVkXdYgrLwNEqwurzlSfCnL3bHY8GloqqFHA89UKpp.paO	jed@example.com	\N	2026-04-21 01:14:53
SL234003	Chems	Camara	San Vicente Sumilao Bukidnon	09601165837	F	2003-10-27	234325345	Regular	200000	2026-04-24	B001	$2y$12$TelyBM2h9kgBMTr69v.O6Ol6Qo1mDwKxGgXF31iVMchwLcu4zRVRC	chems@gmail.com	\N	\N
S003	Tim Joseph	Enriquez	Carmen, CDO	0956-452-4565	M	2005-05-12	NIN246	Manager	500000	2025-03-14	B001	$2y$12$.LZg2iqDtiVhGLqJ0yTT2O2TW1hQONq7YSb7aSOo4YIh0Zm7LHyYK	tim@example.com	\N	2026-05-09 02:26:02
\.


--
-- TOC entry 4081 (class 0 OID 16577)
-- Dependencies: 244
-- Data for Name: support_ticket; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.support_ticket (ticketid, renterno, leaseno, issue_type, message, status, created_at) FROM stdin;
ST1778499346	R201	L301	Lease Inquiry	trial	Open	2026-05-11 11:35:46
\.


--
-- TOC entry 4082 (class 0 OID 16587)
-- Dependencies: 245
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, renterno) FROM stdin;
1	John Doe	client@gmail.com	\N	$2y$12$gfDDIdDOyxBxlAn1wROhwOYpqIqOOcdc6nPtTWzHmi9jGNVYVAD9O	\N	2026-04-27 08:51:55	2026-04-27 08:51:55	R201
\.


--
-- TOC entry 4084 (class 0 OID 16597)
-- Dependencies: 247
-- Data for Name: viewing; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.viewing (viewingid, propertyno, renterno, view_date, comment) FROM stdin;
\.


--
-- TOC entry 4096 (class 0 OID 0)
-- Dependencies: 224
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 4097 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 4098 (class 0 OID 0)
-- Dependencies: 231
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 4, true);


--
-- TOC entry 4099 (class 0 OID 0)
-- Dependencies: 246
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 7, true);


--
-- TOC entry 3816 (class 2606 OID 16608)
-- Name: advertisement advertisement_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advertisement
    ADD CONSTRAINT advertisement_pkey PRIMARY KEY (advertid);


--
-- TOC entry 3818 (class 2606 OID 16610)
-- Name: branch branch_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.branch
    ADD CONSTRAINT branch_pkey PRIMARY KEY (branchno);


--
-- TOC entry 3824 (class 2606 OID 16612)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 3821 (class 2606 OID 16614)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 3826 (class 2606 OID 16616)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3828 (class 2606 OID 16618)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 3830 (class 2606 OID 16620)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 3832 (class 2606 OID 16622)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3835 (class 2606 OID 16624)
-- Name: lease_agreement lease_agreement_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lease_agreement
    ADD CONSTRAINT lease_agreement_pkey PRIMARY KEY (leaseno);


--
-- TOC entry 3837 (class 2606 OID 16626)
-- Name: manager manager_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.manager
    ADD CONSTRAINT manager_pkey PRIMARY KEY (staffno);


--
-- TOC entry 3839 (class 2606 OID 16628)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 3841 (class 2606 OID 16630)
-- Name: newspaper newspaper_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newspaper
    ADD CONSTRAINT newspaper_pkey PRIMARY KEY (newspaperid);


--
-- TOC entry 3843 (class 2606 OID 16632)
-- Name: next_of_kin next_of_kin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.next_of_kin
    ADD CONSTRAINT next_of_kin_pkey PRIMARY KEY (kinid);


--
-- TOC entry 3845 (class 2606 OID 16634)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 3847 (class 2606 OID 16636)
-- Name: payment payment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment
    ADD CONSTRAINT payment_pkey PRIMARY KEY (paymentid);


--
-- TOC entry 3877 (class 2606 OID 16819)
-- Name: payment_submission payment_submission_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment_submission
    ADD CONSTRAINT payment_submission_pkey PRIMARY KEY (submissionid);


--
-- TOC entry 3851 (class 2606 OID 16638)
-- Name: property_inspection property_inspection_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_inspection
    ADD CONSTRAINT property_inspection_pkey PRIMARY KEY (inspectionid);


--
-- TOC entry 3853 (class 2606 OID 16640)
-- Name: property_owner property_owner_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_owner
    ADD CONSTRAINT property_owner_pkey PRIMARY KEY (ownerid);


--
-- TOC entry 3849 (class 2606 OID 16642)
-- Name: property property_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT property_pkey PRIMARY KEY (propertyno);


--
-- TOC entry 3855 (class 2606 OID 16644)
-- Name: renewal_request renewal_request_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renewal_request
    ADD CONSTRAINT renewal_request_pkey PRIMARY KEY (requestid);


--
-- TOC entry 3857 (class 2606 OID 16646)
-- Name: renter renter_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renter
    ADD CONSTRAINT renter_pkey PRIMARY KEY (renterno);


--
-- TOC entry 3859 (class 2606 OID 16648)
-- Name: secretary secretary_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.secretary
    ADD CONSTRAINT secretary_pkey PRIMARY KEY (staffno);


--
-- TOC entry 3862 (class 2606 OID 16650)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 3865 (class 2606 OID 16652)
-- Name: staff staff_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_email_key UNIQUE (email);


--
-- TOC entry 3867 (class 2606 OID 16654)
-- Name: staff staff_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_pkey PRIMARY KEY (staffno);


--
-- TOC entry 3869 (class 2606 OID 16656)
-- Name: support_ticket support_ticket_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.support_ticket
    ADD CONSTRAINT support_ticket_pkey PRIMARY KEY (ticketid);


--
-- TOC entry 3871 (class 2606 OID 16658)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 3873 (class 2606 OID 16660)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3875 (class 2606 OID 16662)
-- Name: viewing viewing_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.viewing
    ADD CONSTRAINT viewing_pkey PRIMARY KEY (viewingid);


--
-- TOC entry 3819 (class 1259 OID 16663)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 3822 (class 1259 OID 16664)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 3833 (class 1259 OID 16665)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 3860 (class 1259 OID 16666)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 3863 (class 1259 OID 16667)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 3907 (class 2620 OID 16668)
-- Name: lease_agreement trg_check_overdue; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_check_overdue BEFORE INSERT OR UPDATE ON public.lease_agreement FOR EACH ROW EXECUTE FUNCTION public.trg_flag_overdue_lease();


--
-- TOC entry 3908 (class 2620 OID 16669)
-- Name: payment trg_payment_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trg_payment_insert BEFORE INSERT ON public.payment FOR EACH ROW EXECUTE FUNCTION public.trg_update_lease_on_payment();


--
-- TOC entry 3878 (class 2606 OID 16670)
-- Name: advertisement advertisement_newspaperid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advertisement
    ADD CONSTRAINT advertisement_newspaperid_fkey FOREIGN KEY (newspaperid) REFERENCES public.newspaper(newspaperid);


--
-- TOC entry 3879 (class 2606 OID 16675)
-- Name: advertisement advertisement_propertyno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.advertisement
    ADD CONSTRAINT advertisement_propertyno_fkey FOREIGN KEY (propertyno) REFERENCES public.property(propertyno) ON UPDATE CASCADE;


--
-- TOC entry 3886 (class 2606 OID 16680)
-- Name: property fk_property_branch; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT fk_property_branch FOREIGN KEY (branchno) REFERENCES public.branch(branchno) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3887 (class 2606 OID 16685)
-- Name: property fk_property_owner; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT fk_property_owner FOREIGN KEY (ownerno) REFERENCES public.property_owner(ownerid) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3888 (class 2606 OID 16690)
-- Name: property fk_property_staff; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT fk_property_staff FOREIGN KEY (staffno) REFERENCES public.staff(staffno) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 3902 (class 2606 OID 16695)
-- Name: users fk_users_renterno; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_users_renterno FOREIGN KEY (renterno) REFERENCES public.renter(renterno) ON DELETE SET NULL;


--
-- TOC entry 3880 (class 2606 OID 16700)
-- Name: lease_agreement lease_agreement_propertyno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lease_agreement
    ADD CONSTRAINT lease_agreement_propertyno_fkey FOREIGN KEY (propertyno) REFERENCES public.property(propertyno) ON UPDATE CASCADE;


--
-- TOC entry 3881 (class 2606 OID 16705)
-- Name: lease_agreement lease_agreement_renterno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lease_agreement
    ADD CONSTRAINT lease_agreement_renterno_fkey FOREIGN KEY (renterno) REFERENCES public.renter(renterno);


--
-- TOC entry 3882 (class 2606 OID 16710)
-- Name: lease_agreement lease_agreement_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lease_agreement
    ADD CONSTRAINT lease_agreement_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3883 (class 2606 OID 16715)
-- Name: manager manager_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.manager
    ADD CONSTRAINT manager_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3884 (class 2606 OID 16720)
-- Name: next_of_kin next_of_kin_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.next_of_kin
    ADD CONSTRAINT next_of_kin_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3885 (class 2606 OID 16725)
-- Name: payment payment_leaseno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment
    ADD CONSTRAINT payment_leaseno_fkey FOREIGN KEY (leaseno) REFERENCES public.lease_agreement(leaseno);


--
-- TOC entry 3905 (class 2606 OID 16820)
-- Name: payment_submission payment_submission_leaseno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment_submission
    ADD CONSTRAINT payment_submission_leaseno_fkey FOREIGN KEY (leaseno) REFERENCES public.lease_agreement(leaseno);


--
-- TOC entry 3906 (class 2606 OID 16825)
-- Name: payment_submission payment_submission_renterno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.payment_submission
    ADD CONSTRAINT payment_submission_renterno_fkey FOREIGN KEY (renterno) REFERENCES public.renter(renterno);


--
-- TOC entry 3889 (class 2606 OID 16730)
-- Name: property property_branchno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT property_branchno_fkey FOREIGN KEY (branchno) REFERENCES public.branch(branchno);


--
-- TOC entry 3892 (class 2606 OID 16735)
-- Name: property_inspection property_inspection_propertyno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_inspection
    ADD CONSTRAINT property_inspection_propertyno_fkey FOREIGN KEY (propertyno) REFERENCES public.property(propertyno);


--
-- TOC entry 3893 (class 2606 OID 16740)
-- Name: property_inspection property_inspection_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property_inspection
    ADD CONSTRAINT property_inspection_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3890 (class 2606 OID 16745)
-- Name: property property_ownerno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT property_ownerno_fkey FOREIGN KEY (ownerno) REFERENCES public.property_owner(ownerid);


--
-- TOC entry 3891 (class 2606 OID 16750)
-- Name: property property_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.property
    ADD CONSTRAINT property_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3894 (class 2606 OID 16755)
-- Name: renewal_request renewal_request_leaseno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renewal_request
    ADD CONSTRAINT renewal_request_leaseno_fkey FOREIGN KEY (leaseno) REFERENCES public.lease_agreement(leaseno);


--
-- TOC entry 3895 (class 2606 OID 16760)
-- Name: renewal_request renewal_request_renterno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renewal_request
    ADD CONSTRAINT renewal_request_renterno_fkey FOREIGN KEY (renterno) REFERENCES public.renter(renterno);


--
-- TOC entry 3896 (class 2606 OID 16765)
-- Name: renter renter_branchno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renter
    ADD CONSTRAINT renter_branchno_fkey FOREIGN KEY (branchno) REFERENCES public.branch(branchno);


--
-- TOC entry 3897 (class 2606 OID 16770)
-- Name: renter renter_witness_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.renter
    ADD CONSTRAINT renter_witness_staffno_fkey FOREIGN KEY (witness_staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3898 (class 2606 OID 16775)
-- Name: secretary secretary_staffno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.secretary
    ADD CONSTRAINT secretary_staffno_fkey FOREIGN KEY (staffno) REFERENCES public.staff(staffno);


--
-- TOC entry 3899 (class 2606 OID 16780)
-- Name: staff staff_branchno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_branchno_fkey FOREIGN KEY (branchno) REFERENCES public.branch(branchno);


--
-- TOC entry 3900 (class 2606 OID 16785)
-- Name: support_ticket support_ticket_leaseno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.support_ticket
    ADD CONSTRAINT support_ticket_leaseno_fkey FOREIGN KEY (leaseno) REFERENCES public.lease_agreement(leaseno);


--
-- TOC entry 3901 (class 2606 OID 16790)
-- Name: support_ticket support_ticket_renterno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.support_ticket
    ADD CONSTRAINT support_ticket_renterno_fkey FOREIGN KEY (renterno) REFERENCES public.renter(renterno);


--
-- TOC entry 3903 (class 2606 OID 16795)
-- Name: viewing viewing_propertyno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.viewing
    ADD CONSTRAINT viewing_propertyno_fkey FOREIGN KEY (propertyno) REFERENCES public.property(propertyno);


--
-- TOC entry 3904 (class 2606 OID 16800)
-- Name: viewing viewing_renterno_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.viewing
    ADD CONSTRAINT viewing_renterno_fkey FOREIGN KEY (renterno) REFERENCES public.renter(renterno);


-- Completed on 2026-05-14 11:36:55 PST

--
-- PostgreSQL database dump complete
--

\unrestrict y94pQsSMURBm81DqaSW0aqqYYyXp4RdEcSGrY4NkpRAnYlPdLVxWgoehpYyOtYQ

